# Guide de Test API Agriforb - Agriculteur

## Prérequis
- Laravel serve lancé : `php artisan serve --host=127.0.0.1 --port=8001`
- Base de données migrée et seedée : `php artisan migrate --seed`

## 1. Authentification

### 1.1 Inscription Agriculteur
**POST** `http://127.0.0.1:8001/api/register`

Body (JSON) :
```json
{
  "username": "jean_dupont",
  "name": "Jean Dupont",
  "email": "jean@example.com",
  "password": "SecurePass123!",
  "password_confirmation": "SecurePass123!",
  "region": "Dakar",
  "experience_level": "intermediaire"
}
```
**Réponse attendue : 201**
```json
{
  "success": true,
  "user": { "id": 1, "username": "...", "role": "agriculteur", ... },
  "token": "1|.......",
  "message": "User registered successfully"
}
```
→ **Copier le token** (sans le guillemets)

---

### 1.2 Login
**POST** `http://127.0.0.1:8001/api/login`

Body (JSON) :
```json
{
  "email": "jean@example.com",
  "password": "SecurePass123!"
}
```
**Réponse : 200** avec token

---

## 2. Headers APIdog

Dans APIdog, pour chaque requête authentifiée, ajouter le header :

`Authorization: Bearer VOTRE_TOKEN_ICI`

---

## 3. Endpoints Agriculteur

### 3.1 Profil
**GET** `http://127.0.0.1:8001/api/farmer/profile`
Header : Authorization
**Réponse : 200**
```json
{
  "success": true,
  "data": {
    "profile": { "id": 1, "username": "...", ... },
    "statistics": {
      "total_parcels": 0,
      "total_surface_ha": 0,
      "cultures_cultivated": 0
    }
  }
}
```

**PUT** `http://127.0.0.1:8001/api/farmer/profile`
Body :
```json
{
  "region": "Thiès",
  "experience_level": "expert"
}
```

---

### 3.2 Parcelles

**GET** `http://127.0.0.1:8001/api/parcels`
Liste paginée de mes parcelles (15 par page)

**POST** `http://127.0.0.1:8001/api/parcels`
Body :
```json
{
  "nom": "Parcelle Test",
  "surface": 2.5,
  "status": "grow",
  "latitude": 14.7167,
  "longitude": -17.4678
}
```
**Réponse : 201** avec parcelle créée

**GET** `http://127.0.0.1:8001/api/parcels/1`
Détail d'une parcelle (remplacer 1 par l'ID)

**PUT** `http://127.0.0.1:8001/api/parcels/1`
Body (champs à mettre à jour) :
```json
{
  "nom": "Parcelle Nord",
  "health_score": 85.5
}
```

**DELETE** `http://127.0.0.1:8001/api/parcels/1`
Supprime la parcelle

---

### 3.3 Météo

**GET** `http://127.0.0.1:8001/api/parcels/1/weather`
Météo actuelle de la parcelle 1

**GET** `http://127.0.0.1:8001/api/parcels/1/weather/forecast`
Prévisions 5 jours

*(Simulé pour l'instant — intégration API météo à venir)*

---

### 3.4 Catalogue Cultures

**GET** `http://127.0.0.1:8001/api/cultures`
Liste complète des culturesavec produits associés

**GET** `http://127.0.0.1:8001/api/cultures/1`
Détail d'une culture (remplacer 1)

*(Read-only pour agriculteur, admin seul peut modifier)*

---

### 3.5 Catalogue Produits

**GET** `http://127.0.0.1:8001/api/products`
Liste complète des produits

**GET** `http://127.0.0.1:8001/api/products/1`
Détail d'un produit

---

### 3.6 Assistant IA (placeholder)

**POST** `http://127.0.0.1:8001/api/ai/chat`
Body :
```json
{
  "message": "Quel engrais pour les tomates ?"
}
```
**Réponse : 200** (réponse simulée en attendant DeepSeek)

**GET** `http://127.0.0.1:8001/api/ai/history`
Historique des interactions

**DELETE** `http://127.0.0.1:8001/api/ai/history/1`
Supprimer une interaction

---

## 4. Routes Admin (test avec compte admin)

Login admin :
- Email : `admin@agriforb.com`
- Password : `Admin@2024`

**POST** `http://127.0.0.1:8001/api/cultures` → 403 (agriculteur)
**POST** `http://127.0.0.1:8001/api/products` → 403 (agriculteur)

Avec token admin, CRUD complet disponible.

---

## 5. Tests d'Authorization

### 5.1 Farmer ne peut pas voir parcelles d'autrui
1. Créer farmer A (tokenA)
2. Créer farmer B (tokenB)
3. Farmer A crée une parcelle → ID X
4. Farmer B tente GET `/api/parcels/X` → **403 Forbidden**

### 5.2 Admin voit toutes parcelles
Avec token admin, GET `/api/parcels/1` fonctionne (mais route non définie pour admin — à étendre).

---

## 6. Test Rapide Automatisé

Exécuter : `php test_farmer_api.php` (après avoir démarré le serveur)

---

## 7. points d'attention

- Toutes les routes agriculteur sont sous `auth:sanctum` + `role:agriculteur`
- Les policies vérifient l'ownership
- La météo est simulée (retour données fixes)
- L'IA retourne un message placeholder
- L'approbation de compte (`is_approved`) n'est pas encore enforced

---

## 8. Prochaines Étapes (aperçu)

- Intégration API météo réelle (OpenWeather)
- DeepSeek API pour réponses IA contextualisées
- Module Rapports (technicien)
- Validation admin des comptes
- Upload image pour diagnostic IA
- Inertia dashboard admin

---

**Bonne exploration !** 🚜
