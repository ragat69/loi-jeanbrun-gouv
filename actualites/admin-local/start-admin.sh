#!/bin/bash
# Start Local Blog Admin Interface
# For Mac and Linux

cd "$(dirname "$0")/../.."

echo "========================================="
echo "  Blog Admin - Starting Local Server"
echo "========================================="
echo ""
echo "Server will start on: http://localhost:8080"
echo "Admin interface: http://localhost:8080/actualites/admin-local/"
echo ""
echo "Press Ctrl+C to stop the server"
echo ""
echo "========================================="
echo ""

php -S localhost:8080
