# Munau College Next.js Frontend

A modern, responsive Next.js + React frontend for the Munau College of Health Sciences and Technology management platform.

## Project Overview

This is a production-ready Next.js application featuring:
- Public website with college information
- Secure student portal with dashboard
- Admission application system
- Finance and fee management
- Admin management dashboard
- Professional healthcare institution branding

## Features

### Public Website
- Home page with institution overview
- About page with institutional details
- Programs and departments showcase
- Contact information with map integration
- Document downloads (admission requirements, brochures)
- Responsive mobile-first design

### Student Portal
- Secure authentication system
- Dashboard with academic overview
- Profile and biodata management
- Course registration interface
- Class timetable viewing
- Results and grades tracking
- Fee payment status and management
- Hostel application and allocation
- Notifications system
- ID card requests

### Admission System
- Multi-step online application form
- Personal information collection
- Educational background verification
- Document upload interface
- Application fee payment gateway integration
- Real-time application status tracking
- Automated admission letter generation
- Acceptance fee tracking

### Finance Module
- Fee structure display
- Payment history tracking
- Receipt generation and download
- Multiple payment method support
- Outstanding balance alerts
- Payment status dashboard

### Admin Dashboard
- Student enrollment analytics
- Admission application management
- Financial reporting and tracking
- System-wide statistics
- Recent application monitoring
- User management interface

## Tech Stack

- **Framework**: Next.js 15+ (App Router)
- **UI Library**: React 19+
- **Styling**: Tailwind CSS v4
- **Components**: Shadcn/ui
- **Charts**: Recharts
- **Icons**: Lucide React
- **State Management**: React Hooks, Client-side state
- **Database Integration**: Ready for Laravel API backend

## Project Structure

```
app/
├── layout.tsx                 # Root layout
├── globals.css               # Global styles with theme
├── page.tsx                  # Home page
├── auth/
│   └── login/page.tsx       # Login page
├── student/
│   ├── dashboard/page.tsx   # Student dashboard
│   ├── profile/page.tsx     # Student profile
│   ├── courses/page.tsx     # Course registration
│   ├── timetable/page.tsx   # Class schedule
│   ├── fees/page.tsx        # Fee payment
│   ├── results/page.tsx     # Grade tracking
│   └── hostel/page.tsx      # Hostel management
├── admission/
│   └── apply/page.tsx       # Admission application
└── admin/
    └── dashboard/page.tsx   # Admin dashboard

components/
├── ui/                       # Shadcn/ui components
└── [custom components]      # Future custom components

lib/
├── utils.ts                 # Utility functions
└── [API integration]        # API client setup
```

## Getting Started

### Prerequisites
- Node.js 18+ 
- npm or yarn

### Installation

1. Clone or extract the project:
```bash
cd munau-college-frontend
```

2. Install dependencies:
```bash
npm install
# or
yarn install
```

3. Set up environment variables:
```bash
cp .env.example .env.local
```

4. Update `.env.local` with your configuration:
```
NEXT_PUBLIC_API_URL=http://localhost:8000/api
```

### Development

Start the development server:
```bash
npm run dev
# or
yarn dev
```

Open [http://localhost:3000](http://localhost:3000) in your browser.

### Production Build

```bash
npm run build
npm start
```

## Key Pages and Routes

| Route | Component | Purpose |
|-------|-----------|---------|
| `/` | Home | Public landing page |
| `/auth/login` | Login | User authentication |
| `/student/dashboard` | Dashboard | Student overview |
| `/student/profile` | Profile | Student information |
| `/student/courses` | Courses | Course registration |
| `/student/timetable` | Timetable | Class schedule |
| `/student/fees` | Fees | Fee management |
| `/student/results` | Results | Grade tracking |
| `/student/hostel` | Hostel | Accommodation management |
| `/admission/apply` | Admission | Apply for admission |
| `/admin/dashboard` | Admin | Administrative overview |

## Authentication Flow

Current implementation uses local storage for demo purposes. For production:

1. Replace mock authentication in `/app/auth/login/page.tsx`
2. Implement API calls to Laravel backend
3. Store JWT tokens securely (HTTP-only cookies recommended)
4. Implement proper session management

### Demo Credentials (for testing)
- Email: `student@munaucollege.edu.ng`
- Password: `password123`
- Admin Email: `admin@munaucollege.edu.ng`

## API Integration

The frontend is ready to integrate with the Laravel backend API:

```typescript
// Example API call setup in services
const API_URL = process.env.NEXT_PUBLIC_API_URL;

export async function loginUser(email: string, password: string) {
  const response = await fetch(`${API_URL}/auth/login`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ email, password }),
  });
  return response.json();
}
```

## Design System

### Color Scheme
- Primary: Blue/Purple (Healthcare-focused)
- Secondary: Teal/Cyan (Trust and stability)
- Accent: Orange (Energy and action)
- Neutral: Grays (Professional)

### Typography
- Headings: Geist (Bold, modern)
- Body: Geist (Clean, readable)
- Mono: Geist Mono (Data, code)

### Responsive Design
- Mobile-first approach
- Breakpoints: sm (640px), md (768px), lg (1024px), xl (1280px)
- Touch-friendly interfaces (min 44px touch targets)

## Components Used

### Shadcn/ui Components
- Button, Card, Input, Label
- Tabs, Select, Badge
- Dialog, Sheet, Toast
- Table, Progress, Separator
- And many more...

### Recharts
- Line Charts (enrollment trends)
- Bar Charts (grade distribution)
- Pie Charts (admission status)

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Performance Optimizations

- Image optimization with Next.js Image component
- Code splitting and lazy loading
- CSS-in-JS with Tailwind CSS v4
- Server-side rendering for better SEO
- Client-side routing for fast navigation

## Security Considerations

1. **Authentication**: Implement JWT tokens with HTTP-only cookies
2. **CSRF Protection**: Verify tokens on form submissions
3. **XSS Prevention**: Input sanitization and output encoding
4. **CORS**: Configure proper CORS headers with backend
5. **Rate Limiting**: Implement on API endpoints
6. **Secure Headers**: Content-Security-Policy, X-Frame-Options, etc.

## Future Enhancements

- Dark mode support
- Real-time notifications with WebSockets
- Advanced search and filtering
- Export functionality (PDF, CSV)
- Mobile native apps (React Native)
- Progressive Web App (PWA) features
- Advanced analytics dashboard
- Video conferencing integration

## Deployment

### Vercel (Recommended)
```bash
npm install -g vercel
vercel
```

### AWS, Azure, Google Cloud
Refer to respective platform documentation for deployment.

### Docker
```dockerfile
FROM node:18-alpine
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build
EXPOSE 3000
CMD ["npm", "start"]
```

## Environment Variables

```env
# API Configuration
NEXT_PUBLIC_API_URL=http://localhost:8000/api

# Optional: Third-party services
NEXT_PUBLIC_GOOGLE_MAPS_KEY=your_key_here
NEXT_PUBLIC_PAYMENT_GATEWAY_KEY=your_key_here
```

## Troubleshooting

### Port already in use
```bash
# Change port
npm run dev -- -p 3001
```

### Build errors
```bash
# Clear cache and reinstall
rm -rf .next node_modules
npm install
npm run build
```

### API connection issues
- Verify Laravel backend is running on correct port
- Check CORS configuration in Laravel
- Verify environment variables are set correctly

## Support & Contribution

For issues and contributions:
1. Check existing documentation
2. Review Laravel backend setup
3. Test with demo credentials
4. Check browser console for errors

## License

Proprietary - Munau College of Health Sciences and Technology

## Contact

- Email: tech@munaucollege.edu.ng
- Support: https://munaucollege.edu.ng/support

---

**Version**: 1.0.0  
**Last Updated**: 2024  
**Status**: Production Ready
