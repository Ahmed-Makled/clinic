<?php

namespace App\Services;

use App\Models\Appointment;
use App\Repositories\AppointmentRepository;
use App\Repositories\DoctorRepository;
use App\Repositories\PatientRepository;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AppointmentService extends BaseService
{
    /**
     * @var AppointmentRepository
     */
    protected $appointmentRepository;

    /**
     * @var DoctorRepository
     */
    protected $doctorRepository;

    /**
     * @var PatientRepository
     */
    protected $patientRepository;

    /**
     * Default appointment duration in minutes
     * 
     * @var int
     */
    protected $defaultDuration = 30;

    /**
     * Valid appointment statuses
     * 
     * @var array
     */
    protected $validStatuses = [
        'scheduled',
        'completed',
        'cancelled'
    ];

    /**
     * AppointmentService constructor.
     *
     * @param AppointmentRepository $appointmentRepository
     * @param DoctorRepository $doctorRepository
     * @param PatientRepository $patientRepository
     */
    public function __construct(
        AppointmentRepository $appointmentRepository,
        DoctorRepository $doctorRepository,
        PatientRepository $patientRepository
    ) {
        $this->appointmentRepository = $appointmentRepository;
        $this->doctorRepository = $doctorRepository;
        $this->patientRepository = $patientRepository;
    }

    /**
     * Schedule a new appointment.
     *
     * @param array $data
     * @return array
     */
    public function scheduleAppointment(array $data): array
    {
        try {
            // Validate input data
            $validator = Validator::make($data, [
                'doctor_id' => 'required|exists:doctors,id',
                'patient_id' => 'required|exists:patients,id',
                'scheduled_at' => 'required|date|after:now',
                'notes' => 'nullable|string|max:1000',
                'fees' => 'nullable|numeric|min:0',
                'is_important' => 'boolean',
            ]);

            if ($validator->fails()) {
                return error_response('Validation error', $validator->errors(), 422);
            }

            // Format the scheduled_at timestamp
            $scheduledAt = Carbon::parse($data['scheduled_at']);
            
            // Check if doctor exists
            $doctor = $this->doctorRepository->find($data['doctor_id']);
            
            // Check if patient exists
            $patient = $this->patientRepository->find($data['patient_id']);
            
            // Check for appointment conflicts
            $conflicts = $this->appointmentRepository->findConflictingAppointments(
                $data['doctor_id'],
                $scheduledAt,
                $this->defaultDuration
            );
            
            if ($conflicts->isNotEmpty()) {
                return error_response('The selected time slot is not available. Please choose another time.', null, 409);
            }
            
            // Set default status if not provided
            if (!isset($data['status'])) {
                $data['status'] = 'scheduled';
            }
            
            // Convert scheduled_at to the right format
            $data['scheduled_at'] = $scheduledAt;
            
            // Create the appointment
            $appointment = $this->appointmentRepository->create($data);
            
            // You could trigger events here
            // event(new AppointmentScheduled($appointment));
            
            return success_response($appointment, 'Appointment scheduled successfully');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Reschedule an existing appointment.
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function rescheduleAppointment(int $id, array $data): array
    {
        try {
            // Validate input data
            $validator = Validator::make($data, [
                'scheduled_at' => 'required|date|after:now',
            ]);

            if ($validator->fails()) {
                return error_response('Validation error', $validator->errors(), 422);
            }

            // Get the appointment
            $appointment = $this->appointmentRepository->find($id);
            
            if (!$appointment) {
                return error_response('Appointment not found', null, 404);
            }
            
            if ($appointment->status === 'cancelled') {
                return error_response('Cannot reschedule a cancelled appointment', null, 400);
            }
            
            if ($appointment->status === 'completed') {
                return error_response('Cannot reschedule a completed appointment', null, 400);
            }
            
            // Format the scheduled_at timestamp
            $scheduledAt = Carbon::parse($data['scheduled_at']);
            
            // Check for appointment conflicts
            $conflicts = $this->appointmentRepository->findConflictingAppointments(
                $appointment->doctor_id,
                $scheduledAt,
                $this->defaultDuration,
                $id // Exclude current appointment from conflict check
            );
            
            if ($conflicts->isNotEmpty()) {
                return error_response('The selected time slot is not available. Please choose another time.', null, 409);
            }
            
            // Update the appointment
            $this->appointmentRepository->update($appointment, [
                'scheduled_at' => $scheduledAt,
                'status' => 'scheduled' // Reset to scheduled if it was something else
            ]);
            
            // Refresh the model
            $appointment = $this->appointmentRepository->find($id);
            
            // You could trigger events here
            // event(new AppointmentRescheduled($appointment));
            
            return success_response($appointment, 'Appointment rescheduled successfully');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Update appointment status.
     *
     * @param int $id
     * @param string $status
     * @return array
     */
    public function updateStatus(int $id, string $status): array
    {
        try {
            // Validate status
            if (!in_array($status, $this->validStatuses)) {
                return error_response('Invalid status. Valid statuses are: ' . implode(', ', $this->validStatuses), null, 422);
            }
            
            // Get the appointment
            $appointment = $this->appointmentRepository->find($id);
            
            if (!$appointment) {
                return error_response('Appointment not found', null, 404);
            }
            
            // Check for invalid transitions
            if ($appointment->status === 'cancelled' && $status !== 'scheduled') {
                return error_response('Cancelled appointments can only be rescheduled', null, 400);
            }
            
            if ($appointment->status === 'completed' && $status !== 'cancelled') {
                return error_response('Completed appointments can only be cancelled', null, 400);
            }
            
            // Update the status
            $this->appointmentRepository->updateStatus($id, $status);
            
            // Refresh the model
            $appointment = $this->appointmentRepository->find($id);
            
            // You could trigger events here based on the status change
            // if ($status === 'completed') {
            //     event(new AppointmentCompleted($appointment));
            // } elseif ($status === 'cancelled') {
            //     event(new AppointmentCancelled($appointment));
            // }
            
            return success_response($appointment, 'Appointment status updated successfully');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Cancel an appointment.
     *
     * @param int $id
     * @param array $data optional reason or notes
     * @return array
     */
    public function cancelAppointment(int $id, array $data = []): array
    {
        try {
            // Get the appointment
            $appointment = $this->appointmentRepository->find($id);
            
            if (!$appointment) {
                return error_response('Appointment not found', null, 404);
            }
            
            if ($appointment->status === 'cancelled') {
                return error_response('Appointment is already cancelled', null, 400);
            }
            
            // Update notes if provided
            if (isset($data['notes'])) {
                $this->appointmentRepository->update($appointment, [
                    'notes' => $data['notes']
                ]);
            }
            
            // Cancel the appointment
            $this->appointmentRepository->updateStatus($id, 'cancelled');
            
            // Refresh the model
            $appointment = $this->appointmentRepository->find($id);
            
            // You could trigger events here
            // event(new AppointmentCancelled($appointment));
            
            return success_response($appointment, 'Appointment cancelled successfully');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get doctor's appointments.
     *
     * @param int $doctorId
     * @param array $params
     * @return array
     */
    public function getDoctorAppointments(int $doctorId, array $params = []): array
    {
        try {
            $perPage = $params['per_page'] ?? 15;
            $relations = $params['relations'] ?? ['patient'];
            
            $appointments = $this->appointmentRepository->getDoctorAppointments(
                $doctorId,
                $perPage,
                ['*'],
                $relations
            );
            
            return success_response($appointments);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get patient's appointments.
     *
     * @param int $patientId
     * @param array $params
     * @return array
     */
    public function getPatientAppointments(int $patientId, array $params = []): array
    {
        try {
            $perPage = $params['per_page'] ?? 15;
            $relations = $params['relations'] ?? ['doctor'];
            
            $appointments = $this->appointmentRepository->getPatientAppointments(
                $patientId,
                $perPage,
                ['*'],
                $relations
            );
            
            return success_response($appointments);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get appointments for a specific date.
     *
     * @param string $date
     * @return array
     */
    public function getAppointmentsByDate(string $date): array
    {
        try {
            $parsedDate = Carbon::parse($date);
            
            // Get all appointments for that date
            $appointments = $this->appointmentRepository->model
                ->whereDate('scheduled_at', $parsedDate->toDateString())
                ->with(['doctor', 'patient'])
                ->orderBy('scheduled_at')
                ->get();
                
            return success_response($appointments);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get upcoming appointments.
     *
     * @param int $limit
     * @return array
     */
    public function getUpcomingAppointments(int $limit = 10): array
    {
        try {
            $appointments = $this->appointmentRepository->getUpcomingAppointments(
                ['doctor', 'patient'],
                $limit
            );
            
            return success_response($appointments);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get today's appointments.
     *
     * @return array
     */
    public function getTodayAppointments(): array
    {
        try {
            $appointments = $this->appointmentRepository->getTodayAppointments(['doctor', 'patient']);
            
            return success_response($appointments);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Handle exceptions.
     *
     * @param \Exception $exception
     * @return array
     */
    protected function handleException(\Exception $exception): array
    {
        report($exception);
        
        if ($exception instanceof ValidationException) {
            return error_response('Validation error', $exception->errors(), 422);
        }
        
        return error_response('An error occurred while processing your request: ' . $exception->getMessage(), null, 500);
    }
}

