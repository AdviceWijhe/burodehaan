# Advice 2025 WordPress Template

Een moderne WordPress boilerplate template met Tailwind 4.1, ACF flexible content en Gutenberg ondersteuning.

## Features

- ✅ **Tailwind CSS 4.1** - Moderne utility-first CSS framework
- ✅ **Responsive Design** - Mobiel-first ontwerp
- ✅ **ACF Flexible Content** - Template voor flexibele content blokken
- ✅ **Gutenberg Ready** - Optimaal ondersteuning voor Gutenberg editor
- ✅ **Accessibility** - WCAG richtlijnen gevolgd
- ✅ **SEO Optimized** - Schema markup en meta tags
- ✅ **Performance** - Geoptimaliseerd voor snelheid

## Template Bestanden

### Basis Template Files
- `style.css` - Theme stylesheet met Tailwind en custom CSS
- `functions.php` - Theme functionaliteit en hooks
- `index.php` - Hoofd template bestand
- `header.php` - Site header met navigatie
- `footer.php` - Site footer
- `sidebar.php` - Widget gebied
- `404.php` - Error pagina
- `searchform.php` - Zoekformulier

### Template Pagina's
- `template-flexible.php` - ACF Flexible Content template
- `template-gutenberg.php` - Gutenberg optimized template

### Assets
- `assets/js/main.js` - JavaScript functionaliteit
- `assets/css/` - Aanvullende CSS bestanden (optioneel)

## Installatie

1. **Upload het theme** naar `/wp-content/themes/advice2025/`
2. **Installeer dependencies** en bouw CSS:
   ```bash
   cd wp-content/themes/advice2025
   npm install
   npm run build
   ```
3. **Activeer het theme** in WordPress admin
4. **Installeer ACF Pro** (optioneel voor flexible content)
5. **Configureer menu's** in Appearance > Menus
6. **Stel widgets in** in Appearance > Widgets

## Development

### CSS Build Process

Het theme gebruikt de officiële [Tailwind CLI](https://tailwindcss.com/docs/installation/tailwind-cli) voor CSS compilatie:

- **Development build**: `npm run build`
- **Watch mode** (voor development): `npm run watch`
- **Production build** (geminificeerd): `npm run build:prod`

### File Structure
```
advice2025/
├── src/
│   └── input.css          # Tailwind input bestand
├── assets/
│   ├── css/
│   │   └── style.css      # Gecompileerde CSS (niet in git)
│   └── js/
│       └── main.js        # Theme JavaScript
├── package.json           # NPM configuratie
├── style.css              # WordPress theme info alleen
└── ...                    # Template bestanden
```

## ACF Flexible Content Blokken

Het flexible content template ondersteunt de volgende blok types:

### Text Block (`text_block`)
- `heading` (Text)
- `content` (Wysiwyg Editor)

### Image + Text Block (`image_text`)
- `heading` (Text)
- `content` (Wysiwyg Editor)
- `image` (Image)
- `image_position` (Select: left/right)
- `background_color` (Select: white/gray)
- `button_text` (Text)
- `button_link` (URL)

### Hero Banner (`hero_banner`)
- `heading` (Text)
- `subheading` (Text)
- `background_image` (Image)
- `button_text` (Text)
- `button_link` (URL)

### Cards Grid (`cards_grid`)
- `section_heading` (Text)
- `section_description` (Textarea)
- `cards` (Repeater)
  - `card_title` (Text)
  - `card_content` (Textarea)
  - `card_image` (Image)
  - `card_link` (URL)

## Menu Locaties

- **Primary Menu** - Hoofd navigatie in header
- **Footer Menu** - Links in footer

## Widget Gebieden

- **Sidebar** - Rechter sidebar voor posts/pagina's

## Customization

### Kleuren Aanpassen
Bewerk de CSS variabelen in `style.css`:

```css
:root {
    --color-primary: #2563eb;
    --color-primary-dark: #1d4ed8;
    --color-secondary: #64748b;
}
```

### Fonts Wijzigen
Pas de font import aan in `style.css`:

```css
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
```

### Tailwind Configuratie
Voor custom Tailwind configuratie, vervang de CDN link in `functions.php` door een lokale build.

## JavaScript Functionaliteit

- Responsive mobiele menu
- Smooth scrolling voor anchor links
- Back-to-top button
- Newsletter formulier validatie
- Accessibility verbeteringen
- Lazy loading voor afbeeldingen

## Browser Ondersteuning

- Chrome (laatste 2 versies)
- Firefox (laatste 2 versies)
- Safari (laatste 2 versies)
- Edge (laatste 2 versies)

## Performance Tips

1. **Gebruik WebP afbeeldingen** waar mogelijk
2. **Optimaliseer afbeeldingen** voor web
3. **Minimaliseer plugins** voor betere performance
4. **Gebruik caching** plugins zoals WP Rocket
5. **Optimaliseer database** regelmatig

## Ontwikkeling

Voor lokale ontwikkeling:

1. Clone/download het theme
2. Installeer in WordPress lokale omgeving
3. Maak wijzigingen in theme bestanden
4. Test op verschillende schermformaten

## Ondersteuning

Voor vragen of ondersteuning, neem contact op via:
- Email: [je-email@example.com]
- Website: [je-website.nl]

## Changelog

### v1.0.0
- Initiële release
- Tailwind 4.1 integratie
- ACF Flexible Content ondersteuning
- Gutenberg optimalisatie
- Responsive design
- Accessibility verbeteringen

## Licentie

GPL v2 of hoger - zie WordPress.org voor details.

---

**Gemaakt door Advice** - Een moderne WordPress boilerplate voor snelle website ontwikkeling.
