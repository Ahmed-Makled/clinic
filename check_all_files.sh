#!/bin/zsh
# File checker script for Laravel Clinic Management System
# This script performs comprehensive checks on all project files

# Define colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color
BOLD='\033[1m'

# Initialize counters
TOTAL_FILES=0
PHP_FILES=0
JS_FILES=0
BLADE_FILES=0
JSON_FILES=0
ERRORS_FOUND=0

echo "${BLUE}==========================================${NC}"
echo "${BLUE}  Clinic Management System File Checker  ${NC}"
echo "${BLUE}==========================================${NC}"
echo ""

# Function to print section headers
print_section() {
    echo "${BOLD}${BLUE}$1${NC}"
    echo "${BLUE}$(printf '=%.0s' {1..50})${NC}"
}

# Function to check PHP syntax
check_php_syntax() {
    print_section "Checking PHP Syntax"

    find . -name "*.php" -not -path "./vendor/*" -not -path "./storage/*" | while read -r file; do
        if ! php -l "$file" > /dev/null 2>&1; then
            echo "${RED}✗ Syntax error in: $file${NC}"
            php -l "$file"
            ((ERRORS_FOUND++))
        else
            echo "${GREEN}✓ $file${NC}"
        fi
        ((PHP_FILES++))
    done
}

# Function to check JSON syntax
check_json_syntax() {
    print_section "Checking JSON Syntax"

    find . -name "*.json" -not -path "./vendor/*" -not -path "./node_modules/*" | while read -r file; do
        if ! python3 -m json.tool "$file" > /dev/null 2>&1; then
            echo "${RED}✗ Invalid JSON: $file${NC}"
            ((ERRORS_FOUND++))
        else
            echo "${GREEN}✓ $file${NC}"
        fi
        ((JSON_FILES++))
    done
}

# Function to check for common Laravel issues
check_laravel_issues() {
    print_section "Checking Laravel Issues"

    # Check for missing environment file
    if [ ! -f ".env" ]; then
        echo "${RED}✗ Missing .env file${NC}"
        ((ERRORS_FOUND++))
    else
        echo "${GREEN}✓ .env file exists${NC}"
    fi

    # Check for proper permissions
    if [ ! -w "storage" ]; then
        echo "${RED}✗ Storage directory not writable${NC}"
        ((ERRORS_FOUND++))
    else
        echo "${GREEN}✓ Storage directory is writable${NC}"
    fi

    if [ ! -w "bootstrap/cache" ]; then
        echo "${RED}✗ Bootstrap cache directory not writable${NC}"
        ((ERRORS_FOUND++))
    else
        echo "${GREEN}✓ Bootstrap cache directory is writable${NC}"
    fi
}

# Function to check module structure
check_module_structure() {
    print_section "Checking Module Structure"

    for module_dir in Modules/*/; do
        if [ -d "$module_dir" ]; then
            module_name=$(basename "$module_dir")
            echo "${YELLOW}Checking module: $module_name${NC}"

            # Check for required module files
            required_files=("module.json" "composer.json")
            for req_file in "${required_files[@]}"; do
                if [ -f "$module_dir$req_file" ]; then
                    echo "  ${GREEN}✓ $req_file${NC}"
                else
                    echo "  ${RED}✗ Missing $req_file${NC}"
                    ((ERRORS_FOUND++))
                fi
            done

            # Check for provider
            if [ -d "$module_dir/Providers" ]; then
                echo "  ${GREEN}✓ Providers directory${NC}"
            else
                echo "  ${YELLOW}! No Providers directory${NC}"
            fi
        fi
    done
}

# Function to check for empty directories
check_empty_directories() {
    print_section "Checking for Empty Directories"

    empty_dirs=$(find . -type d -empty -not -path "./vendor/*" -not -path "./node_modules/*" -not -path "./.git/*" 2>/dev/null)

    if [ -z "$empty_dirs" ]; then
        echo "${GREEN}✓ No empty directories found${NC}"
    else
        echo "${YELLOW}Found empty directories:${NC}"
        echo "$empty_dirs" | while read -r dir; do
            echo "  ${YELLOW}• $dir${NC}"
        done
    fi
}

# Function to check for large files
check_large_files() {
    print_section "Checking for Large Files (>10MB)"

    large_files=$(find . -type f -size +10M -not -path "./vendor/*" -not -path "./node_modules/*" -not -path "./.git/*" 2>/dev/null)

    if [ -z "$large_files" ]; then
        echo "${GREEN}✓ No large files found${NC}"
    else
        echo "${YELLOW}Found large files:${NC}"
        echo "$large_files" | while read -r file; do
            size=$(du -h "$file" | cut -f1)
            echo "  ${YELLOW}• $file ($size)${NC}"
        done
    fi
}

# Function to check composer dependencies
check_composer() {
    print_section "Checking Composer"

    if command -v composer > /dev/null 2>&1; then
        echo "${GREEN}✓ Composer is installed${NC}"

        if composer validate --no-check-publish --quiet; then
            echo "${GREEN}✓ composer.json is valid${NC}"
        else
            echo "${RED}✗ composer.json has issues${NC}"
            ((ERRORS_FOUND++))
        fi

        # Check for security vulnerabilities
        if composer audit --quiet > /dev/null 2>&1; then
            echo "${GREEN}✓ No security vulnerabilities found${NC}"
        else
            echo "${YELLOW}! Security vulnerabilities detected${NC}"
            composer audit
        fi
    else
        echo "${RED}✗ Composer not found${NC}"
        ((ERRORS_FOUND++))
    fi
}

# Function to check Laravel artisan
check_artisan() {
    print_section "Checking Laravel Artisan"

    if php artisan --version > /dev/null 2>&1; then
        echo "${GREEN}✓ Artisan is working${NC}"
        echo "  Version: $(php artisan --version | head -n1)"
    else
        echo "${RED}✗ Artisan is not working${NC}"
        ((ERRORS_FOUND++))
    fi

    # Check routes
    if php artisan route:list --quiet > /dev/null 2>&1; then
        route_count=$(php artisan route:list --json | python3 -c "import sys, json; print(len(json.load(sys.stdin)))" 2>/dev/null || echo "unknown")
        echo "${GREEN}✓ Routes are working ($route_count routes)${NC}"
    else
        echo "${RED}✗ Route issues detected${NC}"
        ((ERRORS_FOUND++))
    fi
}

# Function to display summary
display_summary() {
    print_section "Summary"

    # Count total files
    TOTAL_FILES=$(find . -type f -not -path "./vendor/*" -not -path "./node_modules/*" -not -path "./.git/*" | wc -l | tr -d ' ')
    PHP_FILES=$(find . -name "*.php" -not -path "./vendor/*" | wc -l | tr -d ' ')
    JS_FILES=$(find . -name "*.js" -not -path "./vendor/*" -not -path "./node_modules/*" | wc -l | tr -d ' ')
    BLADE_FILES=$(find . -name "*.blade.php" -not -path "./vendor/*" | wc -l | tr -d ' ')
    JSON_FILES=$(find . -name "*.json" -not -path "./vendor/*" -not -path "./node_modules/*" | wc -l | tr -d ' ')

    echo "Total Files Checked: $TOTAL_FILES"
    echo "PHP Files: $PHP_FILES"
    echo "JavaScript Files: $JS_FILES"
    echo "Blade Templates: $BLADE_FILES"
    echo "JSON Files: $JSON_FILES"
    echo ""

    if [ $ERRORS_FOUND -eq 0 ]; then
        echo "${GREEN}${BOLD}✓ All checks passed! No errors found.${NC}"
    else
        echo "${RED}${BOLD}✗ Found $ERRORS_FOUND error(s) that need attention.${NC}"
    fi
}

# Main execution
main() {
    # Check if we're in a Laravel project
    if [ ! -f "artisan" ]; then
        echo "${RED}Error: This doesn't appear to be a Laravel project (no artisan file found)${NC}"
        exit 1
    fi

    echo "${GREEN}Starting comprehensive file check...${NC}"
    echo ""

    # Run all checks
    check_php_syntax
    echo ""
    check_json_syntax
    echo ""
    check_laravel_issues
    echo ""
    check_module_structure
    echo ""
    check_empty_directories
    echo ""
    check_large_files
    echo ""
    check_composer
    echo ""
    check_artisan
    echo ""
    display_summary

    echo ""
    echo "${BLUE}File check completed!${NC}"
}

# Run the main function
main "$@"
