#!/bin/bash
# Manually publish articles to Git
# Usage: ./publish-to-git.sh "commit message"

cd "$(dirname "$0")/../.."

# Default message if none provided
MESSAGE="${1:-Update blog articles}"

echo "========================================="
echo "  Publishing Blog to Git"
echo "========================================="
echo ""
echo "Commit message: $MESSAGE"
echo ""

# Check if there are changes
if git diff --quiet actualites/posts/ actualites/images/ 2>/dev/null; then
    echo "No changes to commit."
    exit 0
fi

# Add files
echo "Adding files..."
git add actualites/posts/
git add actualites/images/

# Commit
echo "Committing..."
git commit -m "$MESSAGE"

# Push
echo "Pushing to remote..."
if git push; then
    echo ""
    echo "========================================="
    echo "  ✓ Successfully published to Git!"
    echo "========================================="
else
    echo ""
    echo "========================================="
    echo "  ✗ Push failed. Check your connection."
    echo "========================================="
    exit 1
fi
