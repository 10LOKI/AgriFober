---
name: code-quality
description: Enforce strict maintainability rules across PHP and TypeScript — guard clauses, strict types, zero magic numbers, descriptive naming, and single-responsibility functions.
user-invocable: true
---

# Code Quality Enforcement — Agrifober

You are enforcing elite-level code maintainability standards for this project. Apply every rule below without exception. Do not suggest "consider" or "you could" — enforce.

## PHP 8 Strict Typing

- Every file opens with `declare(strict_types=1);`.
- Every method and function has typed parameters AND a return type declaration. No `mixed` unless absolutely unavoidable — justify it inline if used.
- Use union types (`int|string`) and nullable types (`?Model`) precisely. Never type-hint `array` alone — use collection classes or typed PHPDoc (`@param Collection<int, Product>`).
- Use named arguments when calling functions with 3+ parameters so call-sites are self-documenting.

## Guard Clauses (Early Returns)

- All validation, authorization, and null-checks must be at the top of the function as guard clauses that return or throw early.
- Never nest happy-path logic inside an `if` block. The deepest indentation for business logic must be ≤ 2 levels.
- Replace every `if/else` that can be expressed as an early return with an early return.

**Reject this:**
```php
function process($order) {
    if ($order) {
        if ($order->isPaid()) {
            // 30 lines of logic
        }
    }
}
```

**Enforce this:**
```php
function process(Order $order): void {
    if (!$order->isPaid()) {
        throw new UnpaidOrderException($order->id);
    }
    // 30 lines of logic at root level
}
```

## Zero Magic Numbers and Strings

- No numeric or string literals inline in logic. Every constant must be named.
- Use PHP `enum` (backed enums for DB storage) or `const` in a dedicated class.
- Acceptable location: `app/Enums/`, `app/Constants/`.

**Reject:** `if ($user->role === 2)`
**Enforce:** `if ($user->role === UserRole::Admin)`

## Descriptive Naming Over Comments

- Variable names must describe their content and unit: `$activeSubscriptionCount`, `$priceInCents`, `$expiresAt`.
- No single-letter variables except loop indices (`$i`, `$k`) in trivial loops.
- Boolean variables and methods must use `is`, `has`, `can`, `should` prefixes: `$isEligible`, `hasActiveSubscription()`.
- A comment that explains WHAT code does is a naming failure — rename until the comment is unnecessary.

## Single-Responsibility Functions

- A function does one thing. If you need "and" to describe it, split it.
- Maximum function length: 20 lines. If a function exceeds this, extract a private helper with a descriptive name.
- No function mutates state AND returns a computed value. Either it's a command (void, mutates) or a query (returns, no side effects).

## TypeScript Interfaces

- All data shapes crossing component or API boundaries must have an explicit `interface` or `type` alias defined in `resources/ts/types/`.
- No `any`. Use `unknown` with type guards if the shape is truly unknown.
- Props interfaces for every React component — no inline object types on props.
- API response shapes must match backend Resource types exactly. If they drift, the TypeScript interface is wrong.

## Checklist Before Marking Code Done

Before you report any task complete, verify:
- [ ] `declare(strict_types=1)` present in every modified PHP file
- [ ] No inline magic numbers or strings
- [ ] No function exceeds 20 lines
- [ ] All early-return guard clauses are at the top
- [ ] TypeScript `any` count is 0
- [ ] All variables are self-describing without comments
