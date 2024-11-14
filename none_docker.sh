#!/bin/bash

# Start the PHP development server
php artisan serve &

# Run the npm development server
npm run dev &

# Start the Laravel Echo server
npx laravel-echo-server start &

# Start the Laravel queue worker
php artisan queue:work &

# Wait for all background processes to finish
wait
