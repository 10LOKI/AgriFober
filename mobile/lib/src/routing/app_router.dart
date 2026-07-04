import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../features/auth/auth_controller.dart';
import '../features/auth/login_screen.dart';
import '../features/auth/register_screen.dart';
import '../features/catalogue/culture_detail_screen.dart';
import '../features/catalogue/cultures_list_screen.dart';
import '../features/catalogue/product_detail_screen.dart';
import '../features/catalogue/products_list_screen.dart';
import '../features/dashboard/dashboard_screen.dart';
import '../features/parcels/parcel_detail_screen.dart';
import '../features/parcels/parcel_form_screen.dart';
import '../features/parcels/parcels_list_screen.dart';

/// Router that redirects based on [AuthState].
/// While status is unknown (bootstrap), shows a splash.
final routerProvider = Provider<GoRouter>((ref) {
  final auth = ref.watch(authControllerProvider);

  return GoRouter(
    initialLocation: '/',
    redirect: (context, state) {
      final loc = state.matchedLocation;
      switch (auth.status) {
        case AuthStatus.unknown:
          return loc == '/splash' ? null : '/splash';
        case AuthStatus.unauthenticated:
          return (loc == '/login' || loc == '/register') ? null : '/login';
        case AuthStatus.authenticated:
          return (loc == '/login' || loc == '/register' || loc == '/splash')
              ? '/'
              : null;
      }
    },
    routes: [
      GoRoute(path: '/splash', builder: (_, __) => const _Splash()),
      GoRoute(path: '/login', builder: (_, __) => const LoginScreen()),
      GoRoute(path: '/register', builder: (_, __) => const RegisterScreen()),
      GoRoute(path: '/', builder: (_, __) => const DashboardScreen()),
      GoRoute(
        path: '/cultures',
        builder: (_, __) => const CulturesListScreen(),
        routes: [
          GoRoute(
            path: ':id',
            builder: (_, state) => CultureDetailScreen(
              cultureId: int.parse(state.pathParameters['id']!),
            ),
          ),
        ],
      ),
      GoRoute(
        path: '/products',
        builder: (_, __) => const ProductsListScreen(),
        routes: [
          GoRoute(
            path: ':id',
            builder: (_, state) => ProductDetailScreen(
              productId: int.parse(state.pathParameters['id']!),
            ),
          ),
        ],
      ),
      GoRoute(
        path: '/parcels',
        builder: (_, __) => const ParcelsListScreen(),
        routes: [
          GoRoute(
            path: 'new',
            builder: (_, __) => const ParcelFormScreen(),
          ),
          GoRoute(
            path: ':id',
            builder: (_, state) => ParcelDetailScreen(
              parcelId: int.parse(state.pathParameters['id']!),
            ),
            routes: [
              GoRoute(
                path: 'edit',
                builder: (_, state) => ParcelFormScreen(
                  parcelId: int.parse(state.pathParameters['id']!),
                ),
              ),
            ],
          ),
        ],
      ),
    ],
  );
});

class _Splash extends StatelessWidget {
  const _Splash();
  @override
  Widget build(BuildContext context) =>
      const Scaffold(body: Center(child: CircularProgressIndicator()));
}
