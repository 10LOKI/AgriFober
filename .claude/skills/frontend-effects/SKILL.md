---
name: frontend-effects
description: Enforce high-performance, accessible React and Tailwind UI — hardware-accelerated animations only, lightweight Framer Motion variants, and mandatory ARIA labels on all interactive animated elements.
user-invocable: true
---

# Frontend Effects & UI Standards — Agrifober

You are enforcing production-grade React and Tailwind UI standards. Every animation, transition, and interactive component must pass both the performance gate and the accessibility gate. A UI that looks good but is unusable is a failure. A UI that is accessible but janky is also a failure. Both must pass simultaneously.

## Performance Gate — Hardware-Accelerated Only

**Allowed CSS properties for animation:**
- `transform` (translate, scale, rotate)
- `opacity`
- `filter` (blur, brightness — use sparingly)
- `clip-path` (for reveal effects)

**Banned CSS properties for animation** — these trigger layout reflow and cause jank:
- `width`, `height`, `max-height`
- `top`, `left`, `right`, `bottom` (use `translateX/Y` instead)
- `margin`, `padding`
- `border-width`
- `font-size`

**Enforcement:** If a Tailwind transition class or inline style animates a layout property, replace it with a `transform`-equivalent. Example: replace animated `height` with a `scaleY` transform on a fixed-height container.

## Tailwind Transition Standards

Use Tailwind's built-in transition utilities as the default for simple state changes:

```jsx
// Correct — opacity + transform, hardware accelerated
<button className="transition-all duration-200 ease-out hover:scale-105 hover:opacity-90">
  Buy Now
</button>

// Reject — animating layout properties
<div className="transition-all duration-300 hover:h-64 h-48">
```

**Timing defaults for Agrifober:**
- Micro-interactions (hover, focus): `duration-150` to `duration-200`
- Page-level transitions: `duration-300` to `duration-400`
- Entrance animations: `duration-500` max — nothing slower on load
- Easing: prefer `ease-out` for entrances, `ease-in-out` for toggles

Never use `transition-all` when you can name the specific property (`transition-opacity`, `transition-transform`). `transition-all` creates unnecessary recalculation overhead.

## Framer Motion — Lightweight Variants Only

When Tailwind CSS transitions are insufficient (staggered lists, exit animations, drag interactions), use Framer Motion with these constraints:

**Use `variants` objects — never inline `animate` objects on every element:**

```tsx
// ENFORCE — variants defined once, shared across children
const cardVariants = {
  hidden: { opacity: 0, y: 16 },
  visible: { opacity: 1, y: 0, transition: { duration: 0.25, ease: 'easeOut' } },
};

<motion.div variants={cardVariants} initial="hidden" animate="visible">
  <ProductCard />
</motion.div>

// REJECT — inline animation objects (bloats render, harder to maintain)
<motion.div animate={{ opacity: 1, y: 0 }} initial={{ opacity: 0, y: 16 }}>
```

**Staggered lists:** Use `staggerChildren` on the parent `variants`, never map with individual delays:

```tsx
const listVariants = {
  visible: { transition: { staggerChildren: 0.06 } },
};
```

**Banned Framer Motion patterns:**
- `layoutId` animations across route changes (causes hydration issues with Inertia.js)
- `AnimatePresence` wrapping entire page sections — scope it tightly to the element entering/exiting
- Physics springs (`type: 'spring'`) on anything that triggers on scroll or repeated interaction — too expensive
- Importing `motion` components not used in the file

## Accessibility Gate — Non-Negotiable

Every animated or interactive component must pass these checks before it ships:

### 1. Respect `prefers-reduced-motion`

All animations must be disabled or reduced when the user has requested it. In Tailwind:

```jsx
// Tailwind — use motion-safe: and motion-reduce: variants
<div className="motion-safe:transition-transform motion-safe:duration-300 motion-reduce:transition-none">
```

In Framer Motion:

```tsx
import { useReducedMotion } from 'framer-motion';

const shouldReduce = useReducedMotion();
const variants = shouldReduce
  ? { hidden: {}, visible: {} }  // no animation
  : { hidden: { opacity: 0, y: 16 }, visible: { opacity: 1, y: 0 } };
```

**This is not optional.** Every `motion.*` component and every CSS transition must have a `motion-reduce` counterpart.

### 2. ARIA Labels on All Animated Interactive Elements

- Every `<button>`, `<a>`, and clickable `<div>`/`<motion.div>` must have an accessible label.
- If the element contains only an icon or visual, add `aria-label="descriptive action"`.
- If the element toggles state (open/close, expand/collapse), add `aria-expanded={isOpen}`.
- If the element triggers a loading state, add `aria-busy={isLoading}`.

```tsx
// ENFORCE
<motion.button
  variants={buttonVariants}
  aria-label="Add product to cart"
  aria-busy={isSubmitting}
  onClick={handleAddToCart}
>
  <ShoppingCartIcon />
</motion.button>

// REJECT — no label, screen reader gets nothing
<motion.div onClick={handleAddToCart} whileHover={{ scale: 1.05 }}>
  <ShoppingCartIcon />
</motion.div>
```

### 3. Focus Visibility

- Never remove the focus ring with `outline-none` unless you replace it with a custom `focus-visible:ring-*` style.
- Animated elements that receive keyboard focus must show the focus ring even mid-animation.
- Tab order must not be broken by absolute/fixed positioned animated overlays.

### 4. No Animation That Flashes

- Any animation involving rapid opacity oscillation or color flashing at > 3Hz is banned (WCAG 2.3.1 — Three Flashes).
- Loading spinners must use smooth continuous rotation, not flicker patterns.

## Component Checklist Before Shipping

Before marking any UI component complete, verify:
- [ ] Animation only uses `transform` or `opacity` (no layout properties)
- [ ] `motion-safe:` / `motion-reduce:` variants applied to all transitions
- [ ] All interactive elements have `aria-label` or visible text label
- [ ] `aria-expanded`, `aria-busy`, `aria-controls` used where state changes
- [ ] Focus ring visible on keyboard navigation (never `outline-none` without replacement)
- [ ] Framer Motion variants defined as named objects, not inline
- [ ] No `transition-all` when specific property transition is possible
- [ ] Animation duration ≤ 500ms for entrances, ≤ 200ms for micro-interactions
