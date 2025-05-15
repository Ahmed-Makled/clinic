#!/bin/zsh
# Build all PlantUML diagrams in the diagrams directory
# This script converts all .puml files to PNG format

# Define colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo "${BLUE}Building all PlantUML diagrams in the diagrams directory...${NC}"

# Create images directory if it doesn't exist
mkdir -p diagrams/images

# Find all .puml files in the diagrams directory and convert them
count=0
failed=0

for puml_file in diagrams/*.puml; do
  if [ -f "$puml_file" ]; then
    basename=$(basename "$puml_file" .puml)
    output_file="diagrams/images/${basename}.png"
    
    echo "${YELLOW}Processing:${NC} $puml_file -> $output_file"
    
    # Run PlantUML to generate the diagram
    plantuml -tpng "$puml_file" -o "$(pwd)/diagrams/images"
    
    if [ $? -eq 0 ]; then
      echo "${GREEN}✓ Successfully generated:${NC} $output_file"
      ((count++))
    else
      echo "${RED}✗ Failed to generate:${NC} $output_file"
      ((failed++))
    fi
  fi
done

echo "\n${BLUE}Summary:${NC}"
echo "${GREEN}Successfully built:${NC} $count diagrams"

if [ $failed -gt 0 ]; then
  echo "${RED}Failed to build:${NC} $failed diagrams"
fi

echo "\nPlantUML diagrams are available in: ${YELLOW}diagrams/images/${NC}"
