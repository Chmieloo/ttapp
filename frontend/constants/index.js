export const BASE_API_URL = process.env.NODE_ENV === 'development'
  ? process.env.API_HOST || 'http://localhost:8888'
  : 'http://localhost:8888'
