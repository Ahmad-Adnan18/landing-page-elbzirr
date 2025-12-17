Walkthrough - Elbzirr Hybrid Web Catalog Setup
1. Project Overview
We have successfully initialized the Elbzirr Hybrid Web Catalog project. The system is built using Laravel 11, FilamentPHP v3 for the backend, and Vue 3 + Inertia.js for the frontend.

2. Completed Work
✅ Backend (FilamentPHP)
Installation: Filament v3 installed and configured.
Admin Panel: Accessible at /admin.
Admin User: Created (admin@elbzirr.com / password).
Resources:
CategoryResource: Manage product categories.
ProductResource: Manage products (Skeleton ready).
StoreSettingResource: Manage global store settings.
✅ Frontend (Vue 3 + Inertia.js)
Stack: Configured Vite with Vue 3 and Inertia.js.
Components:
Navbar.vue
: Responsive navigation bar.
Footer.vue
: Simple footer.
Layouts/AppLayout.vue
: Main layout wrapper.
Pages/Welcome.vue
: Homepage with Hero section.
Pages/Product/Show.vue
: Product detail template.
Routing: 
web.php
 updated to render Inertia pages.
✅ Database
Migrations:
categories: name, slug.
products
: category_id, name, description, image, active.
product_variants: price, currency (TRY/IDR), stock.
store_settings: key, value.
Models: Eloquent relationships defined (Category <-> Product <-> Variant).
3. Verification Steps
Backend Verification
Run the server: php artisan serve.
Visit http://127.0.0.1:8000/admin.
Login with admin@elbzirr.com / password.
Check the sidebar for Categories, Products, and Store Settings.
Frontend Verification
Run Vite (in a separate terminal): npm run dev.
Visit http://127.0.0.1:8000.
You should see the "Welcome to Elbzirr Dimsum" landing page.
4. Known Issues & Notes
PHP Extension: The intl extension is required for Filament. Ensure it is enabled in your php.ini (remove ; from ;extension=intl).
5. Next Steps
 Implement Repeater logic in ProductResource for Variants.
 Build the Product Configurator on the frontend.
 Implement Hybrid Cart logic (Pinia).