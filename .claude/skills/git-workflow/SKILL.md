---
name: git-workflow
description: Enforce strict Agrifober branch naming, Conventional Commits format, and pre-commit safety checks — blocking debug logs, .env secrets, and non-compliant commit messages.
user-invocable: true
---

# Git Workflow Standards — Agrifober

You are the enforcing gatekeeper for all git operations on this project. Before executing any `git commit`, run the full checklist below. Reject and explain any violation. Do not commit until all checks pass.

## Branch Naming Convention

All branches must follow this schema exactly:

| Type | Pattern | Example |
|---|---|---|
| Feature | `feat/agrifober-{issue#}-{slug}` | `feat/agrifober-42-product-catalog` |
| Bug fix | `fix/agrifober-{issue#}-{slug}` | `fix/agrifober-17-cart-total-overflow` |
| Refactor | `refactor/agrifober-{issue#}-{slug}` | `refactor/agrifober-55-service-extraction` |
| Hotfix | `hotfix/agrifober-{issue#}-{slug}` | `hotfix/agrifober-99-payment-crash` |
| Chore | `chore/agrifober-{slug}` | `chore/agrifober-update-dependencies` |

- Slugs are lowercase, hyphen-separated, no underscores.
- Never commit directly to `main` or `develop`. Always work on a named branch.
- Before creating a branch, verify the current branch with `git branch --show-current`.

## Conventional Commits — Mandatory Format

Every commit message must follow:

```
<type>(<scope>): <imperative-mood subject>

[optional body — explain WHY, not WHAT]

[optional footer: Closes #issue, Breaking change note]
```

**Allowed types:** `feat`, `fix`, `refactor`, `perf`, `test`, `docs`, `chore`, `style`, `ci`

**Scope** must be a domain noun from the Agrifober codebase: `products`, `orders`, `auth`, `farmers`, `cart`, `payments`, `admin`, `ui`, `api`, `db`

**Subject rules:**
- Imperative mood: "add", "fix", "remove" — not "added", "fixes", "removing"
- Max 72 characters
- No period at the end
- No vague words: "update", "change", "misc", "stuff", "wip" are **banned**

**Valid examples:**
```
feat(products): add harvest date field to product creation form
fix(cart): correct total price when applying percentage discounts
perf(orders): eager-load farmer relation to eliminate N+1 on order list
refactor(auth): extract approval logic into UserApprovalService
```

**Invalid — reject these:**
```
update stuff
fixed bug
WIP
feat: changes         ← no scope, vague subject
fix(auth): Fixed the thing.  ← past tense, period, vague
```

## Pre-Commit Safety Checklist

Run `git diff --cached` and scan the staged diff before every commit. **Block the commit and report the violation if any of the following are found:**

### 1. Debug Artifacts — Zero Tolerance
Search staged files for:
```
dd(
var_dump(
print_r(
dump(
console.log(
debugger;
ray(
```
If any are found: list the file and line number, refuse to commit, instruct to remove them.

### 2. Secret and Environment Leaks
Scan for patterns that indicate hardcoded secrets:
- Lines containing `APP_KEY=`, `DB_PASSWORD=`, `MAIL_PASSWORD=`, `STRIPE_SECRET=`, or any `_SECRET=`, `_KEY=`, `_TOKEN=` with a non-placeholder value
- Any `.env` file appearing in the staged list (`git diff --cached --name-only`)
- Private key blocks: `-----BEGIN RSA PRIVATE KEY-----`

If found: refuse the commit immediately, instruct to add the file to `.gitignore` and rotate the secret.

### 3. Committed `.env` or Config Files
Verify `.env`, `.env.local`, `.env.production` are **never** staged. Check `git diff --cached --name-only` for these exact filenames.

### 4. Large Binary Files
Flag any staged file over 1MB that is not a tracked migration or seed file. Images, ZIPs, compiled artifacts belong in storage, not git.

### 5. Migration Safety
If a staged file matches `database/migrations/*.php`:
- Confirm the migration has both `up()` and `down()` methods implemented.
- Warn if `down()` contains `Schema::dropIfExists` on a table with existing production data (irreversible by default).
- Warn if the migration drops or renames a column without a corresponding model update in the same commit.

## Commit Execution Protocol

When asked to commit, execute in this order:
1. `git status` — confirm working tree state
2. `git diff --cached` — run the full safety checklist above
3. If checks pass: construct the commit message using Conventional Commits format
4. `git commit -m "..."` using a heredoc to preserve formatting
5. Report the commit hash and message to the user

Never use `--no-verify`. Never amend a pushed commit. Never force-push to `main`.
