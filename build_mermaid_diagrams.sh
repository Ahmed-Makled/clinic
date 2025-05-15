#!/bin/zsh
# Build all Mermaid diagrams in the diagrams directory
# This script converts all Mermaid diagrams in markdown files to PNG format

# Define colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo "${BLUE}Building all Mermaid sequence diagrams in the diagrams directory...${NC}"

# Create images directory if it doesn't exist
mkdir -p diagrams/images

# Temporary directory for extracted mermaid code
mkdir -p /tmp/mermaid_temp

# Find all markdown files that may contain mermaid diagrams
count=0
failed=0

for md_file in diagrams/*.md; do
  if [ -f "$md_file" ]; then
    basename=$(basename "$md_file" .md)
    output_file="diagrams/images/${basename}.png"

    echo "${YELLOW}Processing:${NC} $md_file -> $output_file"

    # Extract Mermaid code from markdown file
    # Find content between ```mermaid and ``` tags
    temp_mermaid_file="/tmp/mermaid_temp/${basename}.mmd"
    sed -n '/```mermaid/,/```/p' "$md_file" | sed '1d;$d' > "$temp_mermaid_file"

    # Check if we found mermaid content
    if [ -s "$temp_mermaid_file" ]; then
      # Use mmdc (Mermaid CLI) to generate the diagram
      # Install if needed: npm install -g @mermaid-js/mermaid-cli
      mmdc -i "$temp_mermaid_file" -o "$output_file" -b transparent

      if [ $? -eq 0 ]; then
        echo "${GREEN}✓ Successfully generated:${NC} $output_file"
        ((count++))
      else
        echo "${RED}✗ Failed to generate:${NC} $output_file"
        ((failed++))
      fi
    else
      echo "${YELLOW}⚠ No Mermaid diagram found in:${NC} $md_file"
    fi
  fi
done

# Clean up temporary files
rm -rf /tmp/mermaid_temp

echo "\n${BLUE}Summary:${NC}"
echo "${GREEN}Successfully built:${NC} $count diagrams"

if [ $failed -gt 0 ]; then
  echo "${RED}Failed to build:${NC} $failed diagrams"
fi

echo "\nMermaid diagrams are available in: ${YELLOW}diagrams/images/${NC}"
