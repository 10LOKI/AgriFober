import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../features/ai/ai_history_screen.dart';
import '../features/ai/chat_screen.dart';
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
import '../features/profile/edit_profile_screen.dart';
import '../features/profile/profile_screen.dart';
import 'nav_shell.dart';

/// Router that redirects based on [AuthState].
/// While status is unknown (bootstrap), shows a splash.
/// Authenticated app lives in a 4-branch bottom-nav shell:
/// Home / Parcels / Assistant / Profile.
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
      StatefulShellRoute.indexedStack(
        builder: (context, state, shell) => NavShell(shell: shell),
        branches: [
          // -- Home --
          StatefulShellBranch(routes: [
            GoRoute(
              path: '/',
              builder: (_, __) => const DashboardScreen(),
              routes: [
                GoRoute(
                  path: 'cultures',
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
                  path: 'products',
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
              ],
            ),
          ]),
          // -- Parcels --
          StatefulShellBranch(routes: [
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
          ]),
          // -- AI Assistant --
          StatefulShellBranch(routes: [
            GoRoute(
              path: '/assistant',
              builder: (_, state) => ChatScreen(
                parcelId: int.tryParse(
                    state.uri.queryParameters['parcel'] ?? ''),
                conversationId: state.uri.queryParameters['conversation'],
              ),
              routes: [
                GoRoute(
                  path: 'history',
                  builder: (_, __) => const AiHistoryScreen(),
                ),
              ],
            ),
          ]),
          // -- Profile --
          StatefulShellBranch(routes: [
            GoRoute(
              path: '/profile',
              builder: (_, __) => const ProfileScreen(),
              routes: [
                GoRoute(
                  path: 'edit',
                  builder: (_, __) => const EditProfileScreen(),
                ),
              ],
            ),
          ]),
        ],
      ),
    ],
  );
});

class _Splash extends StatelessWidget {
  const _Splash();
  @override
  Widget build(BuildContext context) => Scaffold(
        body: Center(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Icon(
                Icons.agriculture,
                size: 64,
                color: Theme.of(context).colorScheme.primary,
              ),
              const SizedBox(height: 16),
              Text('Agrifober',
                  style: Theme.of(context).textTheme.headlineSmall),
              const SizedBox(height: 24),
              const SizedBox(
                width: 28,
                height: 28,
                child: CircularProgressIndicator(strokeWidth: 3),
              ),
            ],
          ),
        ),
      );
}
