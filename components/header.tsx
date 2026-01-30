'use client';

import Image from 'next/image';
import Link from 'next/link';
import { useState } from 'react';
import { Menu, X, ArrowLeft } from 'lucide-react';
import { usePathname, useRouter } from 'next/navigation';

export function Header() {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const router = useRouter();
  const pathname = usePathname();

  const showBack = Boolean(pathname && pathname !== '/');

  return (
    <header className="sticky top-0 z-50 bg-white border-b border-border shadow-sm">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-16">
          {/* Back button (shows on non-root pages) */}
          {showBack && (
            <button
              onClick={() => router.back()}
              className="mr-2 p-2 hover:bg-secondary/50 rounded-md transition"
              title="Go back"
            >
              <ArrowLeft className="w-5 h-5" />
            </button>
          )}

          {/* Logo and Brand */}
          <Link href="/" className="flex items-center gap-3 hover:opacity-80 transition">
            <Image
              src="/logo.png"
              alt="Munau College Logo"
              width={50}
              height={50}
              className="rounded-lg"
            />
            <div className="hidden sm:flex flex-col">
              <span className="font-bold text-primary text-sm">Munau College</span>
              <span className="text-xs text-muted-foreground">Health Sciences & Technology</span>
            </div>
          </Link>

          {/* Desktop Navigation */}
          <nav className="hidden md:flex items-center gap-8">
            <Link href="/" className="text-sm text-foreground hover:text-primary transition">
              Home
            </Link>
            <Link href="/admission/apply" className="text-sm text-foreground hover:text-primary transition">
              Apply Now
            </Link>
            <Link href="/auth/login" className="text-sm text-foreground hover:text-primary transition">
              Student Portal
            </Link>
          </nav>

          {/* Mobile Menu Button */}
          <button
            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
            className="md:hidden p-2 hover:bg-secondary/50 rounded-lg transition"
          >
            {mobileMenuOpen ? (
              <X className="w-6 h-6" />
            ) : (
              <Menu className="w-6 h-6" />
            )}
          </button>
        </div>

        {/* Mobile Navigation */}
        {mobileMenuOpen && (
          <nav className="md:hidden pb-4 flex flex-col gap-2">
            <Link
              href="/"
              className="px-4 py-2 text-sm text-foreground hover:bg-secondary/50 rounded-lg transition"
              onClick={() => setMobileMenuOpen(false)}
            >
              Home
            </Link>
            <Link
              href="/admission/apply"
              className="px-4 py-2 text-sm text-foreground hover:bg-secondary/50 rounded-lg transition"
              onClick={() => setMobileMenuOpen(false)}
            >
              Apply Now
            </Link>
            <Link
              href="/auth/login"
              className="px-4 py-2 text-sm text-foreground hover:bg-secondary/50 rounded-lg transition"
              onClick={() => setMobileMenuOpen(false)}
            >
              Student Portal
            </Link>
          </nav>
        )}
      </div>
    </header>
  );
}
