---
name: backend-architecture
description: Enforce elite Laravel 11/12 architecture — lean controllers, isolated Service classes, Form Request validation, and mandatory Eloquent eager loading to eliminate N+1 queries.
user-invocable: true
---

# Backend Architecture Standards — Agrifober (Laravel 11/12)

You are the enforcing architect for this Laravel application. Every backend change must conform to these standards. Deviations require explicit written justification in the PR description — not in code comments.

## Controller Constraints (Lean Controllers)

Controllers are HTTP adapters, not business logic hosts. A controller method does exactly three things:
1. Delegates to a Form Request or inline validation
2. Calls a single Service method
3. Returns a response (Inertia render, JSON Resource, or redirect)

**Maximum controller method length: 10 lines.**

**Reject any controller method that:**
- Contains an `if/else` beyond a simple authorization check
- Queries the database directly (no `Model::where(...)` in controllers)
- Formats or transforms data (that belongs in Resources or Services)
- Contains more than one Service call

```php
// CORRECT
public function store(StoreProductRequest $request): RedirectResponse
{
    $this->productService->create($request->validated());
    return redirect()->route('products.index');
}

// REJECT — logic, formatting, and DB calls in controller
public function store(Request $request): RedirectResponse
{
    $data = $request->all();
    if ($data['price'] < 0) { $data['price'] = 0; }
    $product = Product::create($data);
    $product->categories()->sync($data['categories']);
    return redirect()->route('products.index');
}
```

## Service Classes — Mandatory for All Business Logic

- Every non-trivial business operation lives in `app/Services/`.
- Service classes are injected via Laravel's constructor DI — never instantiated with `new` in controllers.
- Service methods are named as verb-noun commands: `createProduct()`, `approveUserRegistration()`, `calculateHarvestYield()`.
- A service method owns one business transaction. Use `DB::transaction()` whenever multiple writes must be atomic.
- Services may call Repositories or query Eloquent directly, but never call other Services (prevents circular dependencies and unpredictable side effects).

## Form Requests — Mandatory, No Exceptions

- Every controller action that accepts input (`store`, `update`, custom actions) must use a dedicated `FormRequest` class.
- Inline `$request->validate([...])` in controllers is **banned**.
- Authorization logic (`authorize()`) lives in the Form Request, not the controller.
- Form Requests live in `app/Http/Requests/` namespaced by domain (e.g., `Requests/Products/StoreProductRequest.php`).
- The `rules()` method returns strict rules. Use `Rule::enum()` for backed enums, `Rule::exists()` for FK constraints.

```php
// app/Http/Requests/Products/StoreProductRequest.php
public function rules(): array
{
    return [
        'name'       => ['required', 'string', 'max:255'],
        'price'      => ['required', 'integer', 'min:0'],
        'category'   => ['required', Rule::enum(ProductCategory::class)],
        'farmer_id'  => ['required', Rule::exists('users', 'id')],
    ];
}
```

## Eloquent Optimization — N+1 Is a Build Failure

- Every Eloquent query that returns a collection must declare its eager loads explicitly with `with()`.
- Before shipping any feature that reads related models, run `\DB::enableQueryLog()` in a local test and assert the query count is bounded.
- Use `withCount()` instead of calling `->count()` on a loaded relationship collection.
- Avoid `$model->relationship` (lazy load) inside any loop. This is an automatic rejection criterion in review.
- Use `select(['col1', 'col2'])` on all queries — never `SELECT *` in production code paths.

```php
// REJECT
$products = Product::all();
foreach ($products as $product) {
    echo $product->farmer->name; // N+1
}

// ENFORCE
$products = Product::with(['farmer:id,name', 'category'])
    ->select(['id', 'name', 'price', 'farmer_id', 'category_id'])
    ->get();
```

## API Resources — Mandatory for JSON Responses

- Every API endpoint returns an `Illuminate\Http\Resources\Json\JsonResource` or `ResourceCollection`.
- No `->toArray()`, `response()->json($model)`, or raw model serialization in controllers.
- Resources live in `app/Http/Resources/` namespaced by domain.

## Additional Constraints

- **Soft deletes** on all models representing user-generated data (products, orders, users). Hard deletes require explicit architectural approval.
- **Observers** for cross-cutting concerns (audit logs, notifications) — not inline in services.
- **Events + Listeners** for anything that should decouple (e.g., `OrderPlaced` → send email, update inventory). Never call notification/email logic directly inside a service.
- **Queue all emails and notifications.** No synchronous `Mail::send()` in a web request path.
