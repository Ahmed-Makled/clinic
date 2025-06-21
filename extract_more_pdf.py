#!/usr/bin/env python3
import PyPDF2

def extract_pdf_content():
    with open('Documentation (2).pdf', 'rb') as file:
        reader = PyPDF2.PdfReader(file)
        total_pages = len(reader.pages)
        print(f'Total pages: {total_pages}')
        
        # Extract table of contents and key sections
        toc_text = ""
        for i in range(min(10, total_pages)):
            page = reader.pages[i]
            text = page.extract_text()
            if "Table of Contents" in text or "Contents" in text:
                print(f"\n=== TABLE OF CONTENTS (Page {i+1}) ===")
                print(text)
                toc_text = text
                print("\n" + "="*60)
        
        # Look for specific sections
        sections_to_find = [
            "System Requirements",
            "Functional Requirements", 
            "Non-Functional Requirements",
            "Use Case",
            "Activity Diagram",
            "Sequence Diagram",
            "Class Diagram",
            "Database",
            "Implementation",
            "Testing"
        ]
        
        for section in sections_to_find:
            for i in range(total_pages):
                page = reader.pages[i]
                text = page.extract_text()
                if section.lower() in text.lower():
                    print(f"\n=== {section.upper()} SECTION (Page {i+1}) ===")
                    print(text[:1500])
                    print("\n" + "="*60)
                    break

if __name__ == "__main__":
    extract_pdf_content() 