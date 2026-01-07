<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## API Documentation (Postman Guide)

This section provides a guide for accessing the API endpoints using Postman.

**Base URL:** `http://localhost:8000/api` (Adjust port if necessary)

**Authentication:**
Most endpoints require a Bearer Token.
1.  **Register/Login** to get a token.
2.  In Postman, go to **Authorization** tab.
3.  Select **Type**: `Bearer Token`.
4.  Paste your token.

### 1. Authentication (Public)
| Method | Endpoint | Description | Body (JSON) |
| :--- | :--- | :--- | :--- |
| `POST` | `/register` | Register new user | `fullname`, `email`, `password`, `role` (optional: mentor/student) |
| `POST` | `/login` | Login user | `email`, `password` |

### 2. User & Profile (Protected)
| Method | Endpoint | Description | Body (JSON) |
| :--- | :--- | :--- | :--- |
| `GET` | `/user` | Get current user info | - |
| `POST` | `/logout` | Logout user | - |
| `POST` | `/become-mentor` | Upgrade user to mentor | - |

### 3. Home & Public Data
| Method | Endpoint | Description | Query Params |
| :--- | :--- | :--- | :--- |
| `GET` | `/home` | Get homepage data (latest classes) | - |

### 4. Kelas (Mentor Only)
| Method | Endpoint | Description | Body / Notes |
| :--- | :--- | :--- | :--- |
| `GET` | `/kelas` | List my classes | - |
| `POST` | `/kelas` | Create new class | `nama_kelas`, `description`, `harga`, `kategori`, `thumbnail` (file) |
| `GET` | `/kelas/{id}` | Detail class | - |
| `PUT` | `/kelas/{id}` | Update class | `nama_kelas`, `description`, etc. |
| `DELETE` | `/kelas/{id}` | Delete class | - |

### 5. Materi (Chapters)
| Method | Endpoint | Description | Body / Params |
| :--- | :--- | :--- | :--- |
| `GET` | `/materi` | List materi by class | `?id_kelas={id}` |
| `POST` | `/materi` | Add new chapter | `id_kelas`, `judul_materi`, `urutan` |
| `PUT` | `/materi/{id}` | Update chapter | `judul_materi`, `urutan` |
| `DELETE`| `/materi/{id}` | Delete chapter | - |

### 6. Sub Materi (Content)
| Method | Endpoint | Description | Body / Params |
| :--- | :--- | :--- | :--- |
| `GET` | `/sub-materi` | List content | `?id_materi={id}` |
| `POST` | `/sub-materi` | Add content | `id_materi`, `judul`, `tipe` (video/dokumen), `file` or `url` |
| `PUT` | `/sub-materi/{id}`| Update content | `judul`, `tipe`, etc. |
| `DELETE`| `/sub-materi/{id}`| Delete content | - |

### 7. Keranjang (Cart)
| Method | Endpoint | Description | Body (JSON) |
| :--- | :--- | :--- | :--- |
| `GET` | `/keranjang` | List items in cart | - |
| `POST` | `/keranjang` | Add to cart | `id_kelas` |
| `DELETE`| `/keranjang/{id}` | Remove from cart | - |

### 8. Transaksi & Checkout
| Method | Endpoint | Description | Body (JSON) |
| :--- | :--- | :--- | :--- |
| `GET` | `/metode-pembayaran`| List payment methods | - |
| `POST` | `/transaksi/checkout`| Create transaction (checkout all cart) | `id_metode_pembayaran` |
| `POST` | `/checkout` | Process checkout (Legacy) | - |
| `GET` | `/transaksi/{id}` | Detail transaction | - |
| `POST` | `/transaksi/bayar` | Pay transaction | `id_transaksi`, `id_metode_pembayaran` |

### 9. Mentor Dashboard
| Method | Endpoint | Description | Notes |
| :--- | :--- | :--- | :--- |
| `GET` | `/mentor/dashboard` | Dashboard summary | Stats cards, recent activity |
| `GET` | `/mentor/pendapatan`| Revenue report | - |
| `GET` | `/mentor/reviews` | Student reviews | - |

### 10. Admin Module (Prefix: `/api/admin`)
**Auth Admin:**
| Method | Endpoint | Description | Body |
| :--- | :--- | :--- | :--- |
| `POST` | `/admin/login` | Admin Login | `email`, `password` |
| `POST` | `/admin/logout` | Admin Logout | - |
| `GET` | `/admin/me` | Current Admin Info | - |

**Admin Management:**
| Method | Endpoint | Description | Body / Notes |
| :--- | :--- | :--- | :--- |
| **Dashboard** | | | |
| `GET` | `/admin/dashboard` | Admin Dashboard stats | - |
| **Users** | | | |
| `GET` | `/admin/users` | List Users | - |
| `POST` | `/admin/users` | Create User | `fullname`, `email`, `password`, `role` |
| `GET` | `/admin/users/{id}` | Detail User | - |
| `DELETE`| `/admin/users/{id}` | Delete User | - |
| `PATCH` | `/admin/users/{id}/status` | Ban/Unban User | `status` (active/banned) |
| `PATCH` | `/admin/users/{id}/catatan`| Update User Notes | `catatan` |
| `PATCH` | `/admin/users/{id}/activate`| Activate User | - |
| **Kelas** | | | |
| `GET` | `/admin/kelas` | List All Classes | - |
| `GET` | `/admin/kelas/{id}` | Detail Class | - |
| `DELETE`| `/admin/kelas/{id}` | Delete Class | - |
| `PATCH` | `/admin/kelas/{id}/status` | Approve/Reject Class | `status` (published/rejected) |
| `PATCH` | `/admin/kelas/{id}/catatan`| Class Notes | `catatan` |
| **Laporan (Sales)** | | | |
| `GET` | `/admin/laporan` | Sales Reports | - |
| **Reports (Complaints)**| | | |
| `GET` | `/admin/reports` | List Complaints | - |
| `GET` | `/admin/reports/{id}` | Detail Complaint | - |
| `DELETE`| `/admin/reports/{id}` | Delete Complaint | - |
| `PATCH` | `/admin/reports/{id}/status`| Update Complaint Status | `status` |
| `PATCH` | `/admin/reports/{id}/catatan`| Complaint Notes | `catatan` |
