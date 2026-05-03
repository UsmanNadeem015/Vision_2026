---
name: Nocturnal Gastronomy
colors:
  surface: "#101415"
  surface-dim: "#101415"
  surface-bright: "#363a3b"
  surface-container-lowest: "#0b0f10"
  surface-container-low: "#191c1e"
  surface-container: "#1d2022"
  surface-container-high: "#272a2c"
  surface-container-highest: "#323537"
  on-surface: "#e0e3e5"
  on-surface-variant: "#d1c2d2"
  inverse-surface: "#e0e3e5"
  inverse-on-surface: "#2d3133"
  outline: "#9a8c9b"
  outline-variant: "#4e4350"
  surface-tint: "#edb1ff"
  primary: "#edb1ff"
  on-primary: "#520070"
  primary-container: "#9d50bb"
  on-primary-container: "#fff3fd"
  inverse-primary: "#883ca6"
  secondary: "#bbc3ff"
  on-secondary: "#001b96"
  secondary-container: "#2638ac"
  on-secondary-container: "#a5b0ff"
  tertiary: "#bec6e0"
  on-tertiary: "#283044"
  tertiary-container: "#697188"
  on-tertiary-container: "#f7f6ff"
  error: "#ffb4ab"
  on-error: "#690005"
  error-container: "#93000a"
  on-error-container: "#ffdad6"
  primary-fixed: "#f9d8ff"
  primary-fixed-dim: "#edb1ff"
  on-primary-fixed: "#320046"
  on-primary-fixed-variant: "#6e208c"
  secondary-fixed: "#dfe0ff"
  secondary-fixed-dim: "#bbc3ff"
  on-secondary-fixed: "#000d60"
  on-secondary-fixed-variant: "#2638ac"
  tertiary-fixed: "#dae2fd"
  tertiary-fixed-dim: "#bec6e0"
  on-tertiary-fixed: "#131b2e"
  on-tertiary-fixed-variant: "#3f465c"
  background: "#101415"
  on-background: "#e0e3e5"
  surface-variant: "#323537"
typography:
  headline-xl:
    fontFamily: Epilogue
    fontSize: 48px
    fontWeight: "700"
    lineHeight: "1.1"
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Epilogue
    fontSize: 32px
    fontWeight: "600"
    lineHeight: "1.2"
    letterSpacing: -0.01em
  headline-md:
    fontFamily: Epilogue
    fontSize: 24px
    fontWeight: "600"
    lineHeight: "1.3"
  body-lg:
    fontFamily: Epilogue
    fontSize: 18px
    fontWeight: "400"
    lineHeight: "1.6"
  body-md:
    fontFamily: Epilogue
    fontSize: 16px
    fontWeight: "400"
    lineHeight: "1.6"
  label-sm:
    fontFamily: Epilogue
    fontSize: 14px
    fontWeight: "500"
    lineHeight: "1.4"
    letterSpacing: 0.05em
  label-xs:
    fontFamily: Epilogue
    fontSize: 12px
    fontWeight: "600"
    lineHeight: "1.2"
    letterSpacing: 0.1em
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  unit: 8px
  container-max: 1280px
  gutter: 24px
  margin-mobile: 16px
  margin-desktop: 40px
---

## Brand & Style

The brand personality for this design system is sophisticated, intelligent, and immersive. It targets culinary enthusiasts who appreciate high-tech precision paired with an editorial aesthetic. The UI should evoke a sense of late-night exploration—calm, focused, and premium.

The design style leans heavily into **Glassmorphism** and **Minimalism**. By utilizing translucent layers and vibrant background blurs, the interface feels deep and atmospheric. This approach ensures that the AI-driven content remains the focal point while the container elements provide a high-end, tactile quality without cluttering the visual field.

## Colors

The palette is built on a deep, nocturnal foundation. The background is a smooth, dark blue gradient that transitions subtly into deep purple tones to create a sense of infinite depth.

- **Primary (Purple):** Used for key calls to action and active states, providing a royal, sophisticated highlight.
- **Secondary (Light Blue):** Used for interactive accents, progress indicators, and AI-driven suggestions.
- **Tertiary (Dark Blue):** Serves as the base canvas color, ensuring high contrast for text.
- **Surface Gradients:** Components should utilize a linear gradient (45deg) from Primary to Secondary with low opacity for glass effects.

## Typography

This design system utilizes **Epilogue** exclusively to maintain a contemporary, geometric, and editorial feel. The heavy weights (700, 600) are reserved for headlines to create a strong visual hierarchy against the dark backgrounds. Body text is kept at a comfortable 16px or 18px to ensure legibility within a complex, data-rich environment. Labels use a slightly increased letter-spacing and semi-bold weights for clear categorization and utility.

## Layout & Spacing

The layout follows a **Fluid Grid** model with a 12-column structure for desktop and a 4-column structure for mobile. A strict 8px rhythmic system governs all margins and paddings, ensuring mathematical harmony across the interface.

Whitespace is used aggressively to separate AI-generated suggestions, allowing the sophisticated typography and gradients to breathe. Content containers should maintain generous internal padding (min 24px) to reinforce the premium, spacious brand feel.

## Elevation & Depth

Depth is communicated through **Glassmorphism** and **Tonal Layers** rather than traditional drop shadows. Surfaces closer to the user are more transparent and have a brighter "rim light" (a 1px semi-transparent white top/left border).

Backgrounds use a 40px backdrop blur to maintain legibility over the underlying purple-to-blue gradients. Secondary levels of elevation are achieved by slightly increasing the opacity of the surface fill. Shadows, when used, are extremely diffused and tinted with the Primary Purple hue to avoid a "muddy" look on the dark canvas.

## Shapes

The shape language is consistently **Rounded**, reflecting a modern and approachable technological aesthetic. Standard components use 0.5rem (8px) corners, while larger cards and modals utilize 1.5rem (24px) to create a soft, organic framing for food imagery. This curvature contrasts the geometric rigidity of the Epilogue typeface, balancing technical precision with organic warmth.

## Components

- **Buttons:** Primary buttons feature a vibrant purple-to-light-blue gradient with white text. Secondary buttons use a "ghost" style with a 1px gradient border.
- **Chips:** Small, highly rounded (pill-shaped) elements with a low-opacity light blue fill, used for dietary tags and ingredient filters.
- **Input Fields:** Dark surfaces with a subtle 1px border that glows light blue when focused.
- **Cards:** The hallmark of this design system; they feature a heavy backdrop blur, a 1px white-to-transparent stroke, and high-resolution food photography that "bleeds" into the glass container.
- **AI Ingredient Scanners:** Specialized components featuring pulsed animations in the light blue secondary color to indicate active processing.
- **Recipe Lists:** Minimalist rows separated by low-opacity dividers (10% white), using high-contrast Epilogue labels for quick scanning.
