# Build Scripts

## Color Sync (`sync-colors.js`)

Dit script synchroniseert automatisch de color palette van `functions.php` naar `src/input.css`.

### Hoe het werkt

1. **Bron**: De color palette is gedefinieerd in `functions.php` in de functie `advice2025_get_color_palette()`
2. **Doel**: CSS variabelen worden gegenereerd in `src/input.css` binnen het `:root` block
3. **Automatisch**: Het script draait automatisch voor elke build via de `prebuild` hook

### Handmatig uitvoeren

```bash
npm run sync-colors
```

### Gebruik in workflow

Het script wordt automatisch uitgevoerd bij:
- `npm run build` (via prebuild hook)
- `npm run watch` (direct voor watch start)
- `npm run build:prod` (direct voor productie build)

### Kleuren aanpassen

1. **Pas alleen `functions.php` aan**:
   ```php
   function advice2025_get_color_palette() {
       return array(
           array(
               'name'  => __('Primary', 'advice2025'),
               'slug'  => 'primary',
               'color' => '#FF5822', // Pas hier de kleur aan
           ),
           // ... meer kleuren
       );
   }
   ```

2. **Run het sync script**:
   ```bash
   npm run sync-colors
   ```

3. **Build je CSS**:
   ```bash
   npm run build
   ```

### Belangrijk

- ⚠️ **Pas NIET handmatig de kleuren aan in `src/input.css`** tussen de markers
- ⚠️ De markers `/* AUTO-GENERATED COLORS - DO NOT EDIT MANUALLY */` en `/* END AUTO-GENERATED COLORS */` moeten blijven staan
- ✅ Alle kleur aanpassingen doen in `functions.php`
- ✅ Het script zorgt ervoor dat kleuren gesynchroniseerd blijven tussen PHP en CSS

### CSS Variabelen gebruiken

Na het builden kun je de kleuren in je CSS en Tailwind gebruiken:

```css
.element {
    background-color: var(--color-primary);
    color: var(--color-white);
}
```

Of in Tailwind (via @theme of arbitrary values):

```html
<div class="bg-[var(--color-primary)] text-[var(--color-white)]">
    Content
</div>
```
