# SwiftBite API quick reference

All request bodies are JSON. Login returns a bearer token. Send it as `Authorization: Bearer TOKEN`.

| Method | Endpoint | Purpose |
|---|---|---|
| POST | `/api/register.php` | Register customer/restaurant/courier |
| POST | `/api/login.php` | Sign in and receive API token |
| GET | `/api/restaurants.php` | Browse restaurants |
| GET | `/api/menu.php?restaurant_id=1` | Browse a restaurant menu |
| GET | `/api/orders.php` | List orders visible to current role |
| POST | `/api/orders.php` | Customer creates order |
| PATCH | `/api/order_status.php` | Restaurant/courier updates status |

Example order:
```json
{
  "restaurant_id": 1,
  "items": [
    {"menu_item_id": 2, "quantity": 1},
    {"menu_item_id": 4, "quantity": 2}
  ]
}
```
For a public deployment, add request throttling, token revocation, strict state-transition rules, ownership checks,
API versioning, validation tests and payment integration.
