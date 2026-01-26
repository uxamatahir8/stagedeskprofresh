#!/bin/bash

# Script to replace all Tabler Icons (ti ti-*) with Lucide icons across all blade files
# This ensures design consistency throughout the application

echo "Starting icon replacement across all blade files..."

# Navigate to views directory
cd "$(dirname "$0")/resources/views/dashboard/pages"

# Common icon replacements (most frequent)
echo "Replacing common icons..."

# Home icon in breadcrumbs
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-home"></i>|<i data-lucide="home" style="width: 14px; height: 14px;"></i>|g' {} +

# Plus icon (add buttons)
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-plus"></i>|<i data-lucide="plus" style="width: 16px; height: 16px;"></i>|g' {} +

# Edit/Pencil icons
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-pencil"></i>|<i data-lucide="pencil" style="width: 14px; height: 14px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-edit"></i>|<i data-lucide="edit" style="width: 14px; height: 14px;"></i>|g' {} +

# Trash icon
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-trash text-white"></i>|<i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-trash"></i>|<i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>|g' {} +

# Eye icon (view buttons)
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-eye"></i>|<i data-lucide="eye" style="width: 14px; height: 14px;"></i>|g' {} +

# Check/Circle icons
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-check-circle"></i>|<i data-lucide="check-circle" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-check"></i>|<i data-lucide="check" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-circle-check"></i>|<i data-lucide="check-circle" style="width: 16px; height: 16px;"></i>|g' {} +

# Info/Alert icons
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-info-circle"></i>|<i data-lucide="info" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-alert-circle"></i>|<i data-lucide="alert-circle" style="width: 16px; height: 16px;"></i>|g' {} +

# Calendar icons
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-calendar"></i>|<i data-lucide="calendar" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-calendar-check"></i>|<i data-lucide="calendar-check" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-calendar-off"></i>|<i data-lucide="calendar-off" style="width: 16px; height: 16px;"></i>|g' {} +

# Clock icon
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-clock"></i>|<i data-lucide="clock" style="width: 14px; height: 14px;"></i>|g' {} +

# Communication icons
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-mail"></i>|<i data-lucide="mail" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-phone"></i>|<i data-lucide="phone" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-message-circle"></i>|<i data-lucide="message-circle" style="width: 16px; height: 16px;"></i>|g' {} +

# User icons
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-users"></i>|<i data-lucide="users" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-user-plus"></i>|<i data-lucide="user-plus" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-user-check"></i>|<i data-lucide="user-check" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-user-off"></i>|<i data-lucide="user-x" style="width: 16px; height: 16px;"></i>|g' {} +

# Navigation icons
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-arrow-left"></i>|<i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-external-link"></i>|<i data-lucide="external-link" style="width: 14px; height: 14px;"></i>|g' {} +

# System icons
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-settings"></i>|<i data-lucide="settings" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-bell"></i>|<i data-lucide="bell" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-download"></i>|<i data-lucide="download" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-filter"></i>|<i data-lucide="filter" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-refresh"></i>|<i data-lucide="refresh-cw" style="width: 16px; height: 16px;"></i>|g' {} +

# Business icons
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-building"></i>|<i data-lucide="building" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-credit-card"></i>|<i data-lucide="credit-card" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-currency-dollar"></i>|<i data-lucide="dollar-sign" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-crown"></i>|<i data-lucide="crown" style="width: 16px; height: 16px;"></i>|g' {} +

# Chart/Analytics icons
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-chart-line"></i>|<i data-lucide="line-chart" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-chart-bar"></i>|<i data-lucide="bar-chart" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-chart-pie"></i>|<i data-lucide="pie-chart" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-chart-donut"></i>|<i data-lucide="pie-chart" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-trending-up"></i>|<i data-lucide="trending-up" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-trending-down"></i>|<i data-lucide="trending-down" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-activity"></i>|<i data-lucide="activity" style="width: 16px; height: 16px;"></i>|g' {} +

# Other common icons
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-star"></i>|<i data-lucide="star" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-star-filled"></i>|<i data-lucide="star" class="fill-current" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-microphone"></i>|<i data-lucide="mic" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-bolt"></i>|<i data-lucide="zap" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-list"></i>|<i data-lucide="list" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-layout-dashboard"></i>|<i data-lucide="layout-dashboard" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-map-pin"></i>|<i data-lucide="map-pin" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-world"></i>|<i data-lucide="globe" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-id"></i>|<i data-lucide="id-card" style="width: 16px; height: 16px;"></i>|g' {} +
find . -name "*.blade.php" -type f -exec sed -i 's|<i class="ti ti-share"></i>|<i data-lucide="share-2" style="width: 16px; height: 16px;"></i>|g' {} +

# Fix action-btn to btn-group
find . -name "*.blade.php" -type f -exec sed -i 's|<div class="action-btn">|<div class="btn-group" role="group">|g' {} +

echo "Icon replacement complete!"
echo "Modified files:"
find . -name "*.blade.php" -type f -newer "$0"
