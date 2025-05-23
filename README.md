# Image Gallery Mobile App

A Laravel-based mobile application that demonstrates the integration between [janis-tech/image-gallery](https://github.com/janis-tech/image-gallery) API, Laravel Livewire, and NativePHP.

## Project Overview

This project serves as an example implementation of a mobile app that connects to the Image Gallery microservice API

## Key Features

- **Gallery Management**: Create, view, and manage image galleries
- **Image Management**: Upload, view, edit and delete images
- **AI-Powered Features**: Leverages the image gallery API's AI capabilities:
  - Automatic image captioning 
  - Semantic search using AI embeddings

## Demo Gallery

To quickly test the application with pre-populated images, you can use the following demo entity ID:

```
xFQZBcKqvCIENLvWmbz5esgKzl2XZtGR21TIM3nEPlI0RzhcJtcHS73coooX5ujOxwUOA3ltYPQXsuQDhPHjaY0JyF6mm6bETa7UU1UqzG2b74JSDvg5sj1y
```

Navigate to `Settings > Image Gallery Integration` in the application and paste this entity ID to connect to the demo gallery.

## Todo

- Implement automated tests with 80% threshold in code coverage
- Configure PHPUnit with level 9 strictness
- CI/CD to test and automatically deploy application on demo site
- Add native file browser for image selection in Android and iOS mobile apps