<?php

namespace App\Repositories;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AppointmentRepository extends BaseRepository
{
    /**
     * AppointmentRepository constructor.
     *
     * @param Appointment $model
     */
    public function __construct(Appointment $model)
    {
        parent::__construct($model);
    }

    /**
     * Get appointments by doctor ID with pagination.
     *
     * @param int $doctorId
     * @param int $perPage
     * @param array $columns
     * @param array $relations
     * @return LengthAwarePaginator
     */
    public function getDoctorAppointments(int $doctorId, int $perPage = 15, array $columns = ['*'], array $relations = []): LengthAwarePaginator
    {
        return $this->model->with($relations)
            ->where('doctor_id', $doctorId)
            ->orderBy('scheduled_at', 'desc')
            ->paginate($perPage, $columns);
    }

    /**
     * Get appointments by patient ID with pagination.
     *
     * @param int $patientId
     * @param int $perPage
     * @param array $columns
     * @param array $relations
     * @return LengthAwarePaginator
     */
    public function getPatientAppointments(int $patientId, int $perPage = 15, array $columns = ['*'], array $relations = []): LengthAwarePaginator
    {
        return $this->model->with($relations)
            ->where('patient_id', $patientId)
            ->orderBy('scheduled_at', 'desc')
            ->paginate($perPage, $columns);
    }

    /**
     * Find appointments for a specific doctor and date.
     *
     * @param int $doctorId
     * @param string|Carbon $date
     * @param array $relations
     * @return Collection
     */
    public function findByDoctorAndDate(int $doctorId, $date, array $relations = []): Collection
    {
        $date = $date instanceof Carbon ? $date : Carbon::parse($date);
        
        return $this->model->with($relations)
            ->where('doctor_id', $doctorId)
            ->whereDate('scheduled_at', $date->toDateString())
            ->orderBy('scheduled_at')
            ->get();
    }

    /**
     * Check for conflicting appointments.
     *
     * @param int $doctorId
     * @param Carbon|string $scheduledAt
     * @param int $durationMinutes
     * @param int|null $excludeAppointmentId
     * @return Collection
     */
    public function findConflictingAppointments(int $doctorId, $scheduledAt, int $durationMinutes = 30, ?int $excludeAppointmentId = null): Collection
    {
        $startTime = $scheduledAt instanceof Carbon ? $scheduledAt : Carbon::parse($scheduledAt);
        $endTime = (clone $startTime)->addMinutes($durationMinutes);
        
        $query = $this->model
            ->where('doctor_id', $doctorId)
            ->where('status', 'scheduled')
            ->where(function ($query) use ($startTime, $endTime) {
                // Appointment starts during another appointment
                $query->whereBetween('scheduled_at', [$startTime, $endTime])
                    // Or another appointment starts during this one
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('scheduled_at', '<=', $startTime)
                            ->whereRaw("DATE_ADD(scheduled_at, INTERVAL 30 MINUTE) >= ?", [$startTime]);
                    });
            });
            
        if ($excludeAppointmentId) {
            $query->where('id', '!=', $excludeAppointmentId);
        }
        
        return $query->get();
    }
    
    /**
     * Get upcoming appointments.
     *
     * @param array $relations
     * @param int $limit
     * @return Collection
     */
    public function getUpcomingAppointments(array $relations = [], int $limit = 10): Collection
    {
        return $this->model->with($relations)
            ->upcoming()
            ->limit($limit)
            ->get();
    }
    
    /**
     * Get today's appointments.
     *
     * @param array $relations
     * @return Collection
     */
    public function getTodayAppointments(array $relations = []): Collection
    {
        return $this->model->with($relations)
            ->today()
            ->get();
    }
    
    /**
     * Get appointments by status.
     *
     * @param string $status
     * @param array $relations
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getByStatus(string $status, array $relations = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with($relations)
            ->where('status', $status)
            ->orderBy('scheduled_at', 'desc')
            ->paginate($perPage);
    }
    
    /**
     * Update appointment status.
     *
     * @param int $id
     * @param string $status
     * @return bool
     */
    public function updateStatus(int $id, string $status): bool
    {
        $appointment = $this->find($id);
        $appointment->status = $status;
        return $appointment->save();
    }
}

