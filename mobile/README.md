# Agrifober Mobile (Flutter)

Flutter client for the Agrifober Laravel REST API (`routes/api.php`).
Token auth via Laravel Sanctum, state via Riverpod, networking via Dio.

## Prerequisites

1. Install the Flutter SDK: https://docs.flutter.dev/get-started/install/windows
2. Verify: `flutter doctor` (resolve any Android toolchain warnings)

## First-time setup

The `lib/`, `pubspec.yaml`, and config files are already committed. You only
need Flutter to generate the platform folders (`android/`, `ios/`):

```bash
cd mobile
flutter create . --org com.agrifober --project-name agrifober_mobile --platforms=android,ios
flutter pub get
```

> `flutter create .` only adds the missing platform/scaffolding files. It does
> not overwrite the existing `lib/` or `pubspec.yaml`.

## Run

Start the Laravel API first (from the repo root):

```bash
php artisan serve   # serves http://127.0.0.1:8000
```

Then run the app:

```bash
cd mobile
flutter run
```

### API base URL

Resolved in `lib/src/core/env.dart`:

- Android emulator -> `http://10.0.2.2:8000/api` (host loopback)
- iOS sim / web / desktop -> `http://127.0.0.1:8000/api`
- Physical device -> override with your machine's LAN IP:

```bash
flutter run --dart-define=API_BASE_URL=http://192.168.1.20:8000/api
```

## Architecture

```
lib/
  main.dart                     # ProviderScope + app entry
  src/
    app.dart                    # MaterialApp.router
    core/
      env.dart                  # base URL resolution
      api_client.dart           # Dio + bearer interceptor + envelope unwrap
      api_exception.dart        # normalised errors (422/401/403)
      token_storage.dart        # flutter_secure_storage
      providers.dart            # apiClient / tokenStorage providers
    models/                     # User, Parcel, Culture, Product (match Resources)
    features/
      auth/                     # repository, controller (StateNotifier), login/register
      dashboard/                # repository + FutureProvider + screen
      parcels/                  # repository + list/detail/form (CRUD)
    routing/
      app_router.dart           # go_router with auth redirects
```

## Implemented

- Login -> token persisted in secure storage
- Register (`POST /register`) with per-field validation errors
- Auto-login on boot (validates token via `GET /user`)
- Auth-aware routing (splash / login / register / dashboard / parcels)
- Farmer dashboard (`GET /farmer/dashboard`)
- Parcels CRUD (`/parcels` list, detail, create, edit, delete)
- Culture dropdown in parcel form (`GET /cultures`)
- Logout (`POST /logout`)
- Account-pending state (login 403 message surfaced on login screen)

## Next endpoints to wire

- Product catalogue (`/products`)
- Parcel weather + recommendations (`/parcels/{id}/weather`, `/recommendations`)
- AI chat (`/ai/chat`, history, image analysis)
- Reports (`/reports`)
