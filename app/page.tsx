'use client';

import { useState, useEffect } from 'react';
import Link from 'next/link';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import {
  MenuIcon,
  X,
  ArrowRight,
  BookOpen,
  GraduationCap,
  Users,
  Clock,
  Download,
  Mail,
  Phone,
} from 'lucide-react';

export default function Home() {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const [heroStatus, setHeroStatus] = useState<'idle' | 'loaded' | 'error'>('idle');
  const [heroFetchStatus, setHeroFetchStatus] = useState<number | null>(null);

  useEffect(() => {
    let cancelled = false;
    // Check the public path for the image using a HEAD request to detect server errors / 404s
    fetch('/mun.jpeg', { method: 'HEAD' })
      .then((res) => {
        if (cancelled) return;
        console.log('[v0] HEAD /mun.jpeg', res.status);
        setHeroFetchStatus(res.status);
      })
      .catch((err) => {
        if (cancelled) return;
        console.error('[v0] HEAD /mun.jpeg failed', err);
        setHeroFetchStatus(-1);
      });
    return () => {
      cancelled = true;
    };
  }, []);

  return (
    <div className="min-h-screen bg-background">
      {/* Navigation */}
      <nav className="sticky top-0 z-50 bg-white border-b border-border shadow-sm">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between items-center h-16">
            <Link href="/" className="flex items-center gap-2">
              <img src="/logo.png" alt="Munau College Logo" className="w-15 h-15 rounded-lg object-cover" />
              <span className="font-bold text-foreground hidden sm:inline">
                Munau College
              </span>
            </Link>

            {/* Desktop Menu */}
            <div className="hidden md:flex items-center gap-8">
              <Link
                href="#home"
                className="text-foreground hover:text-primary transition"
              >
                Home
              </Link>
              <Link
                href="#about"
                className="text-foreground hover:text-primary transition"
              >
                About
              </Link>
              <Link
                href="#programs"
                className="text-foreground hover:text-primary transition"
              >
                Programs
              </Link>
              <Link
                href="#contact"
                className="text-foreground hover:text-primary transition"
              >
                Contact
              </Link>
            </div>

            <div className="flex items-center gap-4">
              <Link href="/auth/login">
                <Button variant="outline" size="sm">
                  Login
                </Button>
              </Link>
              <Link href="/admission/apply">
                <Button size="sm">Apply Now</Button>
              </Link>

              {/* Mobile Menu Button */}
              <button
                className="md:hidden"
                onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
              >
                {mobileMenuOpen ? (
                  <X className="w-6 h-6" />
                ) : (
                  <MenuIcon className="w-6 h-6" />
                )}
              </button>
            </div>
          </div>

          {/* Mobile Menu */}
          {mobileMenuOpen && (
            <div className="md:hidden border-t border-border py-4 space-y-3">
              <Link
                href="#home"
                className="block text-foreground hover:text-primary"
              >
                Home
              </Link>
              <Link
                href="#about"
                className="block text-foreground hover:text-primary"
              >
                About
              </Link>
              <Link
                href="#programs"
                className="block text-foreground hover:text-primary"
              >
                Programs
              </Link>
              <Link
                href="#contact"
                className="block text-foreground hover:text-primary"
              >
                Contact
              </Link>
            </div>
          )}
        </div>
      </nav>

      {/* Hero Section */}
      <section
        className="relative py-20 md:py-32 bg-cover bg-center"
        style={{ backgroundImage: "url('/hero.png')" }}
      >
        {/* Overlay */}
        <div className="absolute inset-0 -z-10">
          {/* Darker overlay for better contrast */}
          <div className="absolute inset-0 bg-gradient-to-br from-black/70 via-black/60 to-black/70" />
        </div>

        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center text-white">
            {/* improved contrast */}
            <h1 className="text-4xl md:text-6xl font-bold mb-6 text-balance text-white drop-shadow-2xl bg-black/40 px-4 py-2 rounded-md inline-block">
              Welcome to Munau College of Health Sciences and Technology
            </h1>
            <p className="text-xl text-white/95 mb-8 max-w-2xl mx-auto text-balance bg-black/40 px-4 py-2 rounded-md inline-block">
              Premier institution for health sciences education in Dutse. Nurturing
              healthcare professionals through excellence in teaching and research.
            </p>
            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <Link href="/admission/apply">
                <Button size="lg" className="gap-2">
                  Apply for Admission <ArrowRight className="w-5 h-5" />
                </Button>
              </Link>
              <Link href="/auth/login">
                <Button size="lg" className="gap-2">
                  Student Portal
                </Button>
              </Link>
            </div>
          </div>
        </div>
      </section>

      {/* About Section */}
      <section id="about" className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid md:grid-cols-2 gap-12 items-center">
            <div>
              <h2 className="text-3xl font-bold mb-6 text-foreground">
                About Our Institution
              </h2>
              <p className="text-lg text-muted-foreground mb-4">
                Munau College of Health Sciences and Technology is dedicated to
                producing highly skilled and ethically sound healthcare
                professionals. Our comprehensive programs combine theoretical
                knowledge with practical experience.
              </p>
              <p className="text-lg text-muted-foreground mb-6">
                With state-of-the-art facilities and experienced faculty, we
                ensure our students are well-prepared for the demanding field of
                healthcare.
              </p>
              <Link href="#about">
                <Button variant="outline" className="gap-2 bg-transparent">
                  Learn More <ArrowRight className="w-5 h-5" />
                </Button>
              </Link>
            </div>
            <div className="grid grid-cols-2 gap-6">
              <Card className="p-6 text-center hover:shadow-lg transition">
                <Users className="w-12 h-12 mx-auto mb-4 text-primary" />
                <h3 className="font-semibold text-foreground mb-2">
                  Expert Faculty
                </h3>
                <p className="text-sm text-muted-foreground">
                  Experienced healthcare professionals
                </p>
              </Card>
              <Card className="p-6 text-center hover:shadow-lg transition">
                <BookOpen className="w-12 h-12 mx-auto mb-4 text-primary" />
                <h3 className="font-semibold text-foreground mb-2">
                  Quality Programs
                </h3>
                <p className="text-sm text-muted-foreground">
                  Accredited health sciences courses
                </p>
              </Card>
              <Card className="p-6 text-center hover:shadow-lg transition">
                <Clock className="w-12 h-12 mx-auto mb-4 text-primary" />
                <h3 className="font-semibold text-foreground mb-2">
                  Modern Facilities
                </h3>
                <p className="text-sm text-muted-foreground">
                  State-of-the-art laboratories
                </p>
              </Card>
              <Card className="p-6 text-center hover:shadow-lg transition">
                <GraduationCap className="w-12 h-12 mx-auto mb-4 text-primary" />
                <h3 className="font-semibold text-foreground mb-2">
                  Career Success
                </h3>
                <p className="text-sm text-muted-foreground">
                  High employment rates
                </p>
              </Card>
            </div>
          </div>
        </div>
      </section>

      {/* Programs Section */}
      <section id="programs" className="py-20 bg-secondary/5">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-3xl font-bold mb-4 text-foreground">
              Our Programs
            </h2>
            <p className="text-lg text-muted-foreground max-w-2xl mx-auto">
              Comprehensive healthcare education pathways designed to meet
              industry standards
            </p>
          </div>

          <div className="grid md:grid-cols-3 gap-8">
            {[
              {
                title: 'Nursing Science',
                description:
                  'Comprehensive nursing education preparing students for professional practice',
                icon: Users,
              },
              {
                title: 'Medical Laboratory Science',
                description:
                  'Advanced training in diagnostic and clinical laboratory practices',
                icon: BookOpen,
              },
              {
                title: 'Health Information Management',
                description:
                  'Digital health and medical records management expertise',
                icon: Clock,
              },
            ].map((program, index) => (
              <Card key={index} className="p-8 hover:shadow-lg transition">
                <program.icon className="w-12 h-12 mb-4 text-primary" />
                <h3 className="text-xl font-semibold mb-3 text-foreground">
                  {program.title}
                </h3>
                <p className="text-muted-foreground mb-6">{program.description}</p>
                <Button variant="ghost" className="gap-2">
                  Explore <ArrowRight className="w-4 h-4" />
                </Button>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="bg-primary text-primary-foreground py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h2 className="text-3xl font-bold mb-4">Ready to Start Your Journey?</h2>
          <p className="text-lg mb-8 opacity-90">
            Join our vibrant community of healthcare students and professionals
          </p>
          <Link href="/admission/apply">
            <Button
              size="lg"
              variant="secondary"
              className="gap-2"
            >
              Apply Now <ArrowRight className="w-5 h-5" />
            </Button>
          </Link>
        </div>
      </section>

      {/* Contact Section */}
      <section id="contact" className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid md:grid-cols-2 gap-12">
            <div>
              <h2 className="text-3xl font-bold mb-8 text-foreground">
                Get in Touch
              </h2>
              <div className="space-y-6">
                <div className="flex gap-4">
                  <Phone className="w-6 h-6 text-primary flex-shrink-0 mt-1" />
                  <div>
                    <h3 className="font-semibold text-foreground mb-1">Phone</h3>
                    <p className="text-muted-foreground">+234 (0) 803 123 4567</p>
                  </div>
                </div>
                <div className="flex gap-4">
                  <Mail className="w-6 h-6 text-primary flex-shrink-0 mt-1" />
                  <div>
                    <h3 className="font-semibold text-foreground mb-1">Email</h3>
                    <p className="text-muted-foreground">
                      admissions@munaucollege.edu.ng
                    </p>
                  </div>
                </div>
                <div className="flex gap-4">
                  <BookOpen className="w-6 h-6 text-primary flex-shrink-0 mt-1" />
                  <div>
                    <h3 className="font-semibold text-foreground mb-1">Address</h3>
                    <p className="text-muted-foreground">
                      Dutse, Jigawa State, Nigeria
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <Card className="p-8">
              <h3 className="text-xl font-semibold mb-6 text-foreground">
                Quick Downloads
              </h3>
              <div className="space-y-3">
                {[
                  'Admission Requirements',
                  'Course Brochure',
                  'Application Form',
                  'Fee Schedule',
                ].map((doc, index) => (
                  <button
                    key={index}
                    className="w-full flex items-center justify-between p-3 rounded-lg bg-secondary/10 hover:bg-secondary/20 transition text-foreground"
                  >
                    <span>{doc}</span>
                    <Download className="w-5 h-5" />
                  </button>
                ))}
              </div>
            </Card>
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer className="bg-foreground/5 border-t border-border py-12">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid md:grid-cols-4 gap-8 mb-8">
            <div>
              <h4 className="font-semibold text-foreground mb-4">Quick Links</h4>
              <ul className="space-y-2 text-sm text-muted-foreground">
                <li>
                  <Link href="#about" className="hover:text-primary">
                    About Us
                  </Link>
                </li>
                <li>
                  <Link href="#programs" className="hover:text-primary">
                    Programs
                  </Link>
                </li>
                <li>
                  <Link href="/admission/apply" className="hover:text-primary">
                    Admission
                  </Link>
                </li>
              </ul>
            </div>
            <div>
              <h4 className="font-semibold text-foreground mb-4">For Students</h4>
              <ul className="space-y-2 text-sm text-muted-foreground">
                <li>
                  <Link href="/auth/login" className="hover:text-primary">
                    Student Portal
                  </Link>
                </li>
                <li>
                  <Link href="#contact" className="hover:text-primary">
                    Support
                  </Link>
                </li>
                <li>
                  <Link href="#contact" className="hover:text-primary">
                    Resources
                  </Link>
                </li>
              </ul>
            </div>
            <div>
              <h4 className="font-semibold text-foreground mb-4">Institution</h4>
              <ul className="space-y-2 text-sm text-muted-foreground">
                <li>
                  <Link href="#about" className="hover:text-primary">
                    Management
                  </Link>
                </li>
                <li>
                  <Link href="#about" className="hover:text-primary">
                    Governance
                  </Link>
                </li>
                <li>
                  <Link href="#about" className="hover:text-primary">
                    Vision & Mission
                  </Link>
                </li>
              </ul>
            </div>
            <div>
              <h4 className="font-semibold text-foreground mb-4">Contact</h4>
              <ul className="space-y-2 text-sm text-muted-foreground">
                <li>Phone: +234 (0) 803 123 4567</li>
                <li>Email: info@munaucollege.edu.ng</li>
                <li>Location: Dutse, Jigawa State</li>
              </ul>
            </div>
          </div>
          <div className="border-t border-border pt-8 text-center text-sm text-muted-foreground">
            <p>
              © 2024 Munau College of Health Sciences and Technology. All rights
              reserved.
            </p>
          </div>
        </div>
      </footer>
    </div>
  );
}
