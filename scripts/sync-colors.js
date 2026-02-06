#!/usr/bin/env node

/**
 * Script to sync color palette from functions.php to input.css
 * This ensures CSS variables stay in sync with the PHP color palette
 */

const fs = require('fs');
const path = require('path');

// Paths
const functionsPath = path.join(__dirname, '../functions.php');
const inputCssPath = path.join(__dirname, '../src/input.css');

// Markers for injection
const START_MARKER = '/* AUTO-GENERATED COLORS - DO NOT EDIT MANUALLY */';
const END_MARKER = '/* END AUTO-GENERATED COLORS */';

/**
 * Parse the color palette from functions.php
 */
function parseColorPalette(phpContent) {
    const colors = [];
    
    // Find the advice2025_get_color_palette function
    const functionMatch = phpContent.match(/function advice2025_get_color_palette\(\)\s*{[\s\S]*?return array\(([\s\S]*?)\);[\s\S]*?}/);
    
    if (!functionMatch) {
        throw new Error('Could not find advice2025_get_color_palette function');
    }
    
    const arrayContent = functionMatch[1];
    
    // Match each color array entry
    const colorPattern = /array\s*\(\s*'name'\s*=>\s*__\(['"](.*?)['"]\s*,\s*'advice2025'\s*\)\s*,\s*'slug'\s*=>\s*['"](\w+)['"]\s*,\s*'color'\s*=>\s*['"](.*?)['"]\s*,?\s*\)/g;
    
    let match;
    while ((match = colorPattern.exec(arrayContent)) !== null) {
        colors.push({
            name: match[1],
            slug: match[2],
            color: match[3]
        });
    }
    
    return colors;
}

/**
 * Generate CSS variables from color palette
 */
function generateCssVariables(colors) {
    let css = '\t' + START_MARKER + '\n';
    
    colors.forEach(color => {
        const varName = `--color-${color.slug}`;
        css += `\t${varName}: ${color.color};\n`;
    });
    
    css += '\t' + END_MARKER;
    
    return css;
}

/**
 * Update input.css with new color variables
 */
function updateInputCss(cssContent, newVariables) {
    // Check if markers exist
    const hasMarkers = cssContent.includes(START_MARKER) && cssContent.includes(END_MARKER);
    
    if (hasMarkers) {
        // Replace content between markers
        const regex = new RegExp(
            `\\t${START_MARKER.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')}[\\s\\S]*?${END_MARKER.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')}`,
            'g'
        );
        return cssContent.replace(regex, newVariables);
    } else {
        // Find :root { and insert after it
        const rootMatch = cssContent.match(/(:root\s*{)/);
        if (!rootMatch) {
            throw new Error('Could not find :root declaration in input.css');
        }
        
        const insertPosition = rootMatch.index + rootMatch[0].length;
        return cssContent.slice(0, insertPosition) + 
               '\n' + newVariables + '\n' + 
               cssContent.slice(insertPosition);
    }
}

/**
 * Main function
 */
function main() {
    try {
        console.log('🎨 Syncing color palette from functions.php to input.css...\n');
        
        // Read functions.php
        console.log('📖 Reading functions.php...');
        const phpContent = fs.readFileSync(functionsPath, 'utf8');
        
        // Parse color palette
        console.log('🔍 Parsing color palette...');
        const colors = parseColorPalette(phpContent);
        
        if (colors.length === 0) {
            throw new Error('No colors found in palette');
        }
        
        console.log(`✓ Found ${colors.length} colors:`);
        colors.forEach(color => {
            console.log(`  - ${color.name} (${color.slug}): ${color.color}`);
        });
        
        // Generate CSS variables
        console.log('\n🎨 Generating CSS variables...');
        const cssVariables = generateCssVariables(colors);
        
        // Read input.css
        console.log('📖 Reading input.css...');
        let cssContent = fs.readFileSync(inputCssPath, 'utf8');
        
        // Update input.css
        console.log('✏️  Updating input.css...');
        cssContent = updateInputCss(cssContent, cssVariables);
        
        // Write back to input.css
        fs.writeFileSync(inputCssPath, cssContent, 'utf8');
        
        console.log('✓ input.css updated successfully!\n');
        console.log('💡 Tip: Run "npm run build" to compile the changes\n');
        
    } catch (error) {
        console.error('❌ Error:', error.message);
        process.exit(1);
    }
}

// Run the script
main();
