## Masjid Pedia (Petunjuk Penggunaan)

**1. Rename file .envexample menjadi .env**

**2. Input konfigurasi Database MySQL dan Mail pada file .env**

**3. Jalankan perintah berikut di terminal secara bertahap:**

> composer install

kemudian:

> php artisan install:project

**4. Jalankan perintah berikut untuk menyalakan server:**

> php artisan serve

## Akun Admin

email: admin@gmail.com
password: password

## Rest API

**1. Login Toko**

Endpoint:

> /api/login

Method: **POST**

| Body     | Tipe   | Deskripsi     |
| -------- | ------ | ------------- |
| email    | String | Email Toko    |
| password | String | Password Toko |

**2. Register Toko**

Endpoint:

> /api/register

Method: **POST**

| Body                  | Tipe   | Deskripsi           |
| --------------------- | ------ | ------------------- |
| name                  | String | Nama Toko           |
| email                 | String | Email Toko          |
| password              | String | Password Toko       |
| password_confirmation | String | Konfirmasi Password |

**3. Reset Password**

Endpoint:

> /api/register

Method: **POST**

| Body                  | Tipe   | Deskripsi                |
| --------------------- | ------ | ------------------------ |
| email                 | String | Email Toko               |
| password              | String | Password Baru Toko       |
| password_confirmation | String | Konfirmasi Password Baru |

**4. Data Semua Supplier**

Endpoint:

> /api/suppliers

Method: **GET**

**5. Data Produk Spesifik Supplier**

Endpoint:

> /api/suppliers/ID/products

ID: id supplier yang ingin diketahui data produknya.

Method: **GET**

**6. Request Produk Ke Supplier**

Endpoint:

> /api/suppliers/ID/products

ID: id supplier yang ingin dikirimkan request.

Method: **POST**

| Body     | Tipe                | Deskripsi                                |
| -------- | ------------------- | ---------------------------------------- |
| products | String Array Object | id product dan jumlah yang ingin dipesan |

contoh data products:

    [
        {
    	    "id": 1,
    	    "amount": 10
    	},
    	{
    	    "id": 2,
    	    "amount": 15
    	},
    ]

**5. Data Produk Toko yang Sudah di Aprrove**

Endpoint:

> /api/my-product

## Selesai.
