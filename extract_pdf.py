#!/usr/bin/env python3
import sys

try:
    import PyPDF2
    
    with open('Documentation (2).pdf', 'rb') as file:
        reader = PyPDF2.PdfReader(file)
        print(f'Total pages: {len(reader.pages)}')
        
        # Extract first few pages
        for i in range(min(5, len(reader.pages))):
            page = reader.pages[i]
            text = page.extract_text()
            print(f'\n=== PAGE {i+1} ===')
            print(text[:2000])
            if i < 4:
                print('\n' + '='*50)
                
except ImportError:
    print('PyPDF2 not available. Trying alternative methods...')
    try:
        import fitz  # PyMuPDF
        
        doc = fitz.open('Documentation (2).pdf')
        print(f'Total pages: {len(doc)}')
        
        for i in range(min(5, len(doc))):
            page = doc[i]
            text = page.get_text()
            print(f'\n=== PAGE {i+1} ===')
            print(text[:2000])
            if i < 4:
                print('\n' + '='*50)
        
        doc.close()
        
    except ImportError:
        print('No PDF libraries available. Please install PyPDF2 or PyMuPDF')
        print('pip install PyPDF2')
        
except Exception as e:
    print(f'Error: {e}') 