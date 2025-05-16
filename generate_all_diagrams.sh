#!/bin/zsh
# filepath: /Users/ahmedmakled/Development/clinic/generate_all_diagrams.sh
# This is an all-in-one script that:
# 1. Checks for required dependencies
# 2. Generates all PlantUML diagrams (PNG and SVG)
# 3. Generates all Mermaid diagrams (PNG and SVG)
# 4. Optimizes PNG images
# 5. Updates documentation

# Define colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo "${BLUE}==========================================${NC}"
echo "${BLUE}  Clinic Management System Documentation  ${NC}"
echo "${BLUE}  All-in-one Diagram Generator            ${NC}"
echo "${BLUE}==========================================${NC}"

# -------------------------------------------------
# Clean up from previous runs
# -------------------------------------------------
echo "\n${BLUE}[1/6] Cleaning up from previous runs...${NC}"
if [ -d "/tmp/mermaid_temp" ]; then
  echo "${YELLOW}Removing temporary files from previous runs...${NC}"
  rm -rf /tmp/mermaid_temp
  echo "${GREEN}✓ Temporary files cleaned up.${NC}"
fi

# -------------------------------------------------
# Check Dependencies Section
# -------------------------------------------------
echo "\n${BLUE}[2/6] Checking required dependencies...${NC}"

missing_dependencies=0

# Check PlantUML
if command -v plantuml &> /dev/null; then
  echo "${GREEN}✓ PlantUML is installed.${NC}"
else
  echo "${RED}✗ PlantUML is not installed.${NC}"
  echo "  Please install PlantUML using:"
  echo "  brew install plantuml"
  missing_dependencies=1
fi

# Check Mermaid CLI
if command -v mmdc &> /dev/null; then
  echo "${GREEN}✓ Mermaid CLI is installed.${NC}"
else
  echo "${RED}✗ Mermaid CLI is not installed.${NC}"
  echo "  Please install Mermaid CLI using:"
  echo "  npm install -g @mermaid-js/mermaid-cli"
  missing_dependencies=1
fi

# Check pngquant
if command -v pngquant &> /dev/null; then
  echo "${GREEN}✓ pngquant is installed.${NC}"
else
  echo "${RED}✗ pngquant is not installed.${NC}"
  echo "  Please install pngquant using:"
  echo "  brew install pngquant"
  missing_dependencies=1
fi

if [ $missing_dependencies -ne 0 ]; then
  echo "${RED}Some dependencies are missing. Please install them before continuing.${NC}"
  exit 1
else
  echo "${GREEN}All required dependencies are installed!${NC}"
fi

# Clean up existing files and create directories
echo "\n${YELLOW}Cleaning up existing files and creating output directories...${NC}"

# Remove existing generated files
if [ -d "diagrams/images" ]; then
  echo "${YELLOW}Removing existing diagram files...${NC}"
  rm -rf diagrams/images/*
  echo "${GREEN}✓ Existing diagram files removed.${NC}"
fi

# Create directories if they don't exist
mkdir -p diagrams/images/png
mkdir -p diagrams/images/svg
mkdir -p diagrams/images/png/originals
mkdir -p /tmp/mermaid_temp

# -------------------------------------------------
# Generate PlantUML PNG Diagrams
# -------------------------------------------------
echo "\n${BLUE}[3/6] Generating PlantUML diagrams...${NC}"

# Build PlantUML PNG diagrams
echo "\n${YELLOW}Building PlantUML PNG diagrams...${NC}"
puml_png_count=0
puml_png_failed=0

for puml_file in diagrams/*.puml; do
  if [ -f "$puml_file" ]; then
    basename=$(basename "$puml_file" .puml)
    output_file="diagrams/images/png/${basename}.png"

    echo "${YELLOW}Processing:${NC} $puml_file -> $output_file"

    # Run PlantUML to generate the diagram
    plantuml -tpng "$puml_file" -o "$(pwd)/diagrams/images/png"

    if [ $? -eq 0 ]; then
      echo "${GREEN}✓ Successfully generated:${NC} $output_file"
      ((puml_png_count++))
    else
      echo "${RED}✗ Failed to generate:${NC} $output_file"
      ((puml_png_failed++))
    fi
  fi
done

# Build PlantUML SVG diagrams
echo "\n${YELLOW}Building PlantUML SVG diagrams...${NC}"
puml_svg_count=0
puml_svg_failed=0

for puml_file in diagrams/*.puml; do
  if [ -f "$puml_file" ]; then
    basename=$(basename "$puml_file" .puml)
    output_file="diagrams/images/svg/${basename}.svg"

    echo "${YELLOW}Processing:${NC} $puml_file -> $output_file"

    # Run PlantUML to generate the SVG diagram
    plantuml -tsvg "$puml_file" -o "$(pwd)/diagrams/images/svg"

    if [ $? -eq 0 ]; then
      echo "${GREEN}✓ Successfully generated:${NC} $output_file"
      ((puml_svg_count++))
    else
      echo "${RED}✗ Failed to generate:${NC} $output_file"
      ((puml_svg_failed++))
    fi
  fi
done

# -------------------------------------------------
# Generate Mermaid Diagrams
# -------------------------------------------------
echo "\n${BLUE}[4/6] Generating Mermaid diagrams...${NC}"

# Build Mermaid PNG diagrams
echo "\n${YELLOW}Building Mermaid PNG diagrams...${NC}"
mermaid_png_count=0
mermaid_png_failed=0

for md_file in diagrams/*.md; do
  if [ -f "$md_file" ]; then
    basename=$(basename "$md_file" .md)
    output_file="diagrams/images/png/${basename}.png"

    echo "${YELLOW}Processing:${NC} $md_file -> $output_file"

    # Extract Mermaid code from markdown file
    temp_mermaid_file="/tmp/mermaid_temp/${basename}.mmd"
    sed -n '/```mermaid/,/```/p' "$md_file" | sed '1d;$d' > "$temp_mermaid_file"

    # Check if we found mermaid content
    if [ -s "$temp_mermaid_file" ]; then
      # Use mmdc (Mermaid CLI) to generate the diagram
      mmdc -i "$temp_mermaid_file" -o "$output_file" -b transparent -s 3 -q 100

      if [ $? -eq 0 ]; then
        echo "${GREEN}✓ Successfully generated:${NC} $output_file"
        ((mermaid_png_count++))
      else
        echo "${RED}✗ Failed to generate:${NC} $output_file"
        ((mermaid_png_failed++))
      fi
    else
      echo "${YELLOW}⚠ No Mermaid diagram found in:${NC} $md_file"
    fi
  fi
done

# Build Mermaid SVG diagrams
echo "\n${YELLOW}Building Mermaid SVG diagrams...${NC}"
mermaid_svg_count=0
mermaid_svg_failed=0

for md_file in diagrams/*.md; do
  if [ -f "$md_file" ]; then
    basename=$(basename "$md_file" .md)
    output_file="diagrams/images/svg/${basename}.svg"

    echo "${YELLOW}Processing:${NC} $md_file -> $output_file"

    # Extract Mermaid code from markdown file
    temp_mermaid_file="/tmp/mermaid_temp/${basename}.mmd"
    sed -n '/```mermaid/,/```/p' "$md_file" | sed '1d;$d' > "$temp_mermaid_file"

    # Check if we found mermaid content
    if [ -s "$temp_mermaid_file" ]; then
      # Use mmdc (Mermaid CLI) to generate the diagram as SVG
      mmdc -i "$temp_mermaid_file" -o "$output_file" -b transparent -t neutral

      if [ $? -eq 0 ]; then
        echo "${GREEN}✓ Successfully generated:${NC} $output_file"
        ((mermaid_svg_count++))
      else
        echo "${RED}✗ Failed to generate:${NC} $output_file"
        ((mermaid_svg_failed++))
      fi
    else
      echo "${YELLOW}⚠ No Mermaid diagram found in:${NC} $md_file"
    fi
  fi
done

# Clean up temporary mermaid files
rm -rf /tmp/mermaid_temp

# -------------------------------------------------
# Optimize PNG Images
# -------------------------------------------------
echo "\n${BLUE}[5/6] Optimizing PNG images...${NC}"
optimize_count=0
optimize_failed=0

for png_file in diagrams/images/png/*.png; do
  if [ -f "$png_file" ]; then
    # Extract filename
    filename=$(basename "$png_file")
    backup_file="diagrams/images/png/originals/${filename}"

    echo "${YELLOW}Optimizing:${NC} $png_file"

    # Create backup
    cp "$png_file" "$backup_file"

    # Optimize with pngquant - 256 colors, force overwrite, quality 65-80
    pngquant --force --ext .png --quality=65-80 256 "$png_file"

    if [ $? -eq 0 ]; then
      echo "${GREEN}✓ Successfully optimized:${NC} $png_file"
      ((optimize_count++))
    else
      echo "${RED}✗ Failed to optimize:${NC} $png_file"
      ((optimize_failed++))
      # Restore from backup on failure
      cp "$backup_file" "$png_file"
    fi
  fi
done

# -------------------------------------------------
# Update Documentation
# -------------------------------------------------
echo "\n${BLUE}[6/6] Updating Documentation...${NC}"
echo "${YELLOW}Updating Documentation.html...${NC}"

# Create a symbolic link to the PNG and SVG folders in the public directory if they don't exist
if [ ! -d "public/diagrams" ]; then
  echo "${YELLOW}Creating symbolic link for diagrams in public directory...${NC}"
  mkdir -p public/diagrams
fi

# Copy the diagrams to the public directory for web access
echo "${YELLOW}Copying diagrams to public directory...${NC}"
cp -R diagrams/images/png public/diagrams/
cp -R diagrams/images/svg public/diagrams/

# Check if Documentation_updated.html exists
if [ -f "Documentation_updated.html" ]; then
  cp Documentation_updated.html Documentation.html
  echo "${GREEN}✓ Documentation.html updated successfully!${NC}"
else
  # No custom update file found, we'll create references to the generated images
  echo "${YELLOW}Creating documentation reference for generated images...${NC}"

  # Create a simple HTML reference file
  cat > Documentation_images.html << EOL
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Management System - Diagram Images</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1, h2 { color: #333; }
        .image-container { margin-bottom: 30px; }
        .image-container img { max-width: 100%; border: 1px solid #ddd; margin-top: 10px; }
        .tabs { display: flex; margin-bottom: 10px; }
        .tab { padding: 8px 16px; cursor: pointer; background: #f0f0f0; margin-right: 5px; }
        .tab.active { background: #007bff; color: white; }
    </style>
</head>
<body>
    <h1>Clinic Management System Diagrams</h1>
    <p>This page contains all generated diagram images from the diagrams directory.</p>

    <h2>Generated Diagrams</h2>

    <div class="image-container">
EOL

  # Add PNG images
  echo "        <h3>PNG Images</h3>" >> Documentation_images.html

  for png_file in diagrams/images/png/*.png; do
    if [ -f "$png_file" ]; then
      basename=$(basename "$png_file")
      echo "        <div>" >> Documentation_images.html
      echo "            <h4>$basename</h4>" >> Documentation_images.html
      echo "            <img src=\"public/diagrams/png/$basename\" alt=\"$basename\">" >> Documentation_images.html
      echo "        </div>" >> Documentation_images.html
    fi
  done

  # Add SVG images
  echo "        <h3>SVG Images (Vector Graphics - Higher Quality)</h3>" >> Documentation_images.html

  for svg_file in diagrams/images/svg/*.svg; do
    if [ -f "$svg_file" ]; then
      basename=$(basename "$svg_file")
      echo "        <div>" >> Documentation_images.html
      echo "            <h4>$basename</h4>" >> Documentation_images.html
      echo "            <img src=\"public/diagrams/svg/$basename\" alt=\"$basename\">" >> Documentation_images.html
      echo "        </div>" >> Documentation_images.html
    fi
  done

  echo "    </div>" >> Documentation_images.html
  echo "</body>" >> Documentation_images.html
  echo "</html>" >> Documentation_images.html

  echo "${GREEN}✓ Documentation_images.html created with references to all diagram images!${NC}"
fi

# -------------------------------------------------
# Summary
# -------------------------------------------------
echo "\n${BLUE}==========================================${NC}"
echo "${BLUE}             SUMMARY                     ${NC}"
echo "${BLUE}==========================================${NC}"

echo "${GREEN}Successfully generated:${NC}"
echo "  PlantUML PNG diagrams: $puml_png_count"
echo "  PlantUML SVG diagrams: $puml_svg_count"
echo "  Mermaid PNG diagrams: $mermaid_png_count"
echo "  Mermaid SVG diagrams: $mermaid_svg_count"
echo "  Optimized PNG images: $optimize_count"

failed_total=$((puml_png_failed + puml_svg_failed + mermaid_png_failed + mermaid_svg_failed + optimize_failed))
if [ $failed_total -gt 0 ]; then
  echo "\n${RED}Failed operations:${NC}"
  [ $puml_png_failed -gt 0 ] && echo "  PlantUML PNG diagrams: $puml_png_failed"
  [ $puml_svg_failed -gt 0 ] && echo "  PlantUML SVG diagrams: $puml_svg_failed"
  [ $mermaid_png_failed -gt 0 ] && echo "  Mermaid PNG diagrams: $mermaid_png_failed"
  [ $mermaid_svg_failed -gt 0 ] && echo "  Mermaid SVG diagrams: $mermaid_svg_failed"
  [ $optimize_failed -gt 0 ] && echo "  Failed optimizations: $optimize_failed"
fi

echo "\n${GREEN}PNG diagram images are available in:${NC} ${YELLOW}diagrams/images/png/${NC}"
echo "${GREEN}SVG diagram images are available in:${NC} ${YELLOW}diagrams/images/svg/${NC}"
echo "${BLUE}Original PNG backups are in:${NC} ${YELLOW}diagrams/images/png/originals/${NC}"
echo "\n${GREEN}Documentation has been updated:${NC} ${YELLOW}Documentation.html${NC}"
echo "\n${BLUE}==========================================${NC}"
