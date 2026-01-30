'use client';

import React from "react"

import { useState } from 'react';
import Image from 'next/image';
import Link from 'next/link';
import { useRouter } from 'next/navigation';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { GraduationCap, Upload, CheckCircle, AlertCircle } from 'lucide-react';
import { processAdmission, sendAdmissionConfirmationEmail } from '@/app/lib/admission-service';

export default function AdmissionPage() {
  const router = useRouter();
  const [step, setStep] = useState(1);
  const [loading, setLoading] = useState(false);
  const [applicationId, setApplicationId] = useState('');
  const [studentPassword, setStudentPassword] = useState('');
  const [formData, setFormData] = useState({
    firstName: '',
    lastName: '',
    email: '',
    phone: '',
    dateOfBirth: '',
    program: '',
    education: '',
    password: '',
    confirmPassword: '',
  });
  const [uploadedFiles, setUploadedFiles] = useState({
    transcript: null,
    certificate: null,
    birth: null,
  });
  const [uploaded, setUploaded] = useState({
    transcript: false,
    certificate: false,
    birth: false,
  });
  const [passwordError, setPasswordError] = useState('');

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  const handleSelectChange = (name: string, value: string) => {
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  const handleFileUpload = (doc: string, file: File | null) => {
    if (file) {
      setUploadedFiles((prev) => ({ ...prev, [doc]: file }));
      setUploaded((prev) => ({ ...prev, [doc]: true }));
    }
  };

  const validatePassword = () => {
    const password = formData.password;
    const confirmPassword = formData.confirmPassword;

    if (!password || !confirmPassword) {
      setPasswordError('Both password fields are required');
      return false;
    }

    if (password.length < 8) {
      setPasswordError('Password must be at least 8 characters long');
      return false;
    }

    if (!/(?=.*[a-z])/.test(password)) {
      setPasswordError('Password must contain at least one lowercase letter');
      return false;
    }

    if (!/(?=.*[A-Z])/.test(password)) {
      setPasswordError('Password must contain at least one uppercase letter');
      return false;
    }

    if (!/(?=.*\d)/.test(password)) {
      setPasswordError('Password must contain at least one number');
      return false;
    }

    if (password !== confirmPassword) {
      setPasswordError('Passwords do not match');
      return false;
    }

    setPasswordError('');
    return true;
  };

  const generateApplicationId = () => {
    const timestamp = Date.now();
    const random = Math.floor(Math.random() * 10000);
    return `APP-2024-${timestamp}${random}`.slice(0, 15);
  };

  const handleSubmit = async () => {
    if (!validatePassword()) {
      return;
    }

    if (!uploaded.transcript || !uploaded.certificate || !uploaded.birth) {
      alert('Please upload all required documents');
      return;
    }

    setLoading(true);
    try {
      // Process admission: upload files, create account, setup dashboard
      console.log('[v0] Processing admission application...');
      const { applicationId: newAppId, studentId } = await processAdmission(
        {
          firstName: formData.firstName,
          lastName: formData.lastName,
          email: formData.email,
          phone: formData.phone,
          dateOfBirth: formData.dateOfBirth,
          program: formData.program,
          education: formData.education,
          password: formData.password,
        },
        uploadedFiles
      );

      console.log('[v0] Admission processed successfully:', {
        studentId,
        applicationId: newAppId,
        email: formData.email,
      });

      // Set data for success screen
      setApplicationId(newAppId);
      setStudentPassword(formData.password);

      // Send confirmation email asynchronously (don't wait for it)
      sendAdmissionConfirmationEmail(formData.email, {
        name: `${formData.firstName} ${formData.lastName}`,
        studentId,
        applicationId: newAppId,
        program: formData.program,
      }).catch((error) => {
        console.warn('[v0] Email notification failed, but admission was successful');
      });

      // Store user session data temporarily for dashboard access
      if (typeof window !== 'undefined') {
        localStorage.setItem('userEmail', formData.email);
        localStorage.setItem('studentId', studentId);
        localStorage.setItem('applicationId', newAppId);
        localStorage.setItem('studentName', `${formData.firstName} ${formData.lastName}`);
      }

      setStep(4); // Success step
    } catch (error) {
      console.error('[v0] Admission submission failed:', error);
      alert(
        'Failed to submit application. Please try again or contact support.'
      );
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-primary/10 via-background to-secondary/10 py-12 px-4">
      <div className="max-w-4xl mx-auto">
        {/* Header */}
        <div className="text-center mb-12">
          <Link href="/" className="flex justify-center mb-6">
            <Image
              src="/logo.png"
              alt="Munau College Logo"
              width={100}
              height={100}
              className="rounded-lg"
            />
          </Link>
          <h1 className="text-4xl font-bold text-foreground mb-2">
            Apply to Munau College
          </h1>
          <p className="text-lg text-muted-foreground">
            Start your journey in healthcare education
          </p>
        </div>

        {/* Progress Steps */}
        <div className="mb-8">
          <div className="flex items-center justify-between mb-4">
            {[1, 2, 3, 4].map((s) => (
              <div
                key={s}
                className={`flex items-center flex-1 ${s < 4 ? 'gap-2' : ''}`}
              >
                <div
                  className={`w-10 h-10 rounded-full flex items-center justify-center font-bold transition ${
                    step >= s
                      ? 'bg-primary text-primary-foreground'
                      : 'bg-border text-muted-foreground'
                  }`}
                >
                  {s < step ? <CheckCircle className="w-6 h-6" /> : s}
                </div>
                {s < 4 && (
                  <div
                    className={`flex-1 h-1 mx-1 rounded-full transition ${
                      step > s ? 'bg-primary' : 'bg-border'
                    }`}
                  />
                )}
              </div>
            ))}
          </div>
          <div className="flex justify-between text-sm text-muted-foreground">
            <span>Personal Info</span>
            <span>Education</span>
            <span>Documents</span>
            <span>Complete</span>
          </div>
        </div>

        {/* Form Tabs */}
        <Card className="p-8 shadow-lg">
          {step === 1 && (
            <div className="space-y-6">
              <div>
                <h2 className="text-2xl font-bold mb-2 text-foreground">
                  Personal Information
                </h2>
                <p className="text-muted-foreground">
                  Please provide your basic details
                </p>
              </div>

              <div className="grid md:grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="firstName">First Name *</Label>
                  <Input
                    id="firstName"
                    name="firstName"
                    placeholder="John"
                    value={formData.firstName}
                    onChange={handleInputChange}
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="lastName">Last Name *</Label>
                  <Input
                    id="lastName"
                    name="lastName"
                    placeholder="Doe"
                    value={formData.lastName}
                    onChange={handleInputChange}
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="email">Email Address *</Label>
                  <Input
                    id="email"
                    name="email"
                    type="email"
                    placeholder="john@example.com"
                    value={formData.email}
                    onChange={handleInputChange}
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="phone">Phone Number *</Label>
                  <Input
                    id="phone"
                    name="phone"
                    type="tel"
                    placeholder="+234 (0) 803 123 4567"
                    value={formData.phone}
                    onChange={handleInputChange}
                  />
                </div>
                <div className="space-y-2 md:col-span-2">
                  <Label htmlFor="dateOfBirth">Date of Birth *</Label>
                  <Input
                    id="dateOfBirth"
                    name="dateOfBirth"
                    type="date"
                    value={formData.dateOfBirth}
                    onChange={handleInputChange}
                  />
                </div>
              </div>

              <div className="flex gap-4 pt-6">
                <Button
                  variant="outline"
                  onClick={() => router.push('/')}
                  className="flex-1"
                >
                  Cancel
                </Button>
                <Button
                  onClick={() => setStep(2)}
                  disabled={
                    !formData.firstName ||
                    !formData.email ||
                    !formData.phone
                  }
                  className="flex-1"
                >
                  Next Step
                </Button>
              </div>
            </div>
          )}

          {step === 2 && (
            <div className="space-y-6">
              <div>
                <h2 className="text-2xl font-bold mb-2 text-foreground">
                  Educational Background
                </h2>
                <p className="text-muted-foreground">
                  Tell us about your academic qualifications
                </p>
              </div>

              <div className="space-y-4">
                <div className="space-y-2">
                  <Label htmlFor="education">
                    Highest Level of Education *
                  </Label>
                  <Select
                    value={formData.education}
                    onValueChange={(value) =>
                      handleSelectChange('education', value)
                    }
                  >
                    <SelectTrigger>
                      <SelectValue placeholder="Select qualification" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="waec">WAEC/NECO O'Levels</SelectItem>
                      <SelectItem value="utme">UTME</SelectItem>
                      <SelectItem value="post-secondary">
                        Post-Secondary Diploma
                      </SelectItem>
                      <SelectItem value="hnd">HND</SelectItem>
                      <SelectItem value="degree">Bachelor's Degree</SelectItem>
                    </SelectContent>
                  </Select>
                </div>

                <div className="space-y-2">
                  <Label htmlFor="program">Preferred Program *</Label>
                  <Select
                    value={formData.program}
                    onValueChange={(value) =>
                      handleSelectChange('program', value)
                    }
                  >
                    <SelectTrigger>
                      <SelectValue placeholder="Select a program" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="nursing">
                        Nursing Science (RN)
                      </SelectItem>
                      <SelectItem value="midwifery">
                        Midwifery (RM)
                      </SelectItem>
                      <SelectItem value="mls">
                        Medical Laboratory Science
                      </SelectItem>
                      <SelectItem value="health-records">
                        Health Records Management
                      </SelectItem>
                      <SelectItem value="environmental-health">
                        Environmental Health
                      </SelectItem>
                    </SelectContent>
                  </Select>
                </div>

                <div className="bg-secondary/10 border border-secondary/20 rounded-lg p-4">
                  <p className="text-sm text-muted-foreground">
                    Admission Requirements:
                  </p>
                  <ul className="text-sm text-muted-foreground mt-2 space-y-1 list-disc list-inside">
                    <li>JAMB UTME Score: Minimum 140</li>
                    <li>O'Level or equivalent qualifications</li>
                    <li>WAEC/NECO in English and Mathematics</li>
                  </ul>
                </div>
              </div>

              <div className="flex gap-4 pt-6">
                <Button
                  variant="outline"
                  onClick={() => setStep(1)}
                  className="flex-1"
                >
                  Back
                </Button>
                <Button
                  onClick={() => setStep(3)}
                  disabled={!formData.education || !formData.program}
                  className="flex-1"
                >
                  Next Step
                </Button>
              </div>
            </div>
          )}

          {step === 3 && (
            <div className="space-y-6">
              <div>
                <h2 className="text-2xl font-bold mb-2 text-foreground">
                  Credentials & Documents
                </h2>
                <p className="text-muted-foreground">
                  Create your password and upload supporting documents
                </p>
              </div>

              {/* Password Section */}
              <div className="bg-secondary/5 border border-secondary/20 rounded-lg p-6 space-y-4">
                <h3 className="font-semibold text-foreground">Create Your Password</h3>
                <p className="text-sm text-muted-foreground">
                  This password will be used to access your student dashboard
                </p>

                <div className="space-y-2">
                  <Label htmlFor="password">Password *</Label>
                  <Input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="Enter a strong password"
                    value={formData.password}
                    onChange={handleInputChange}
                  />
                  <p className="text-xs text-muted-foreground">
                    Minimum 8 characters, with uppercase, lowercase, and number
                  </p>
                </div>

                <div className="space-y-2">
                  <Label htmlFor="confirmPassword">Confirm Password *</Label>
                  <Input
                    id="confirmPassword"
                    name="confirmPassword"
                    type="password"
                    placeholder="Confirm your password"
                    value={formData.confirmPassword}
                    onChange={handleInputChange}
                  />
                </div>

                {passwordError && (
                  <div className="bg-destructive/10 border border-destructive/20 rounded-lg p-3 flex gap-3">
                    <AlertCircle className="w-5 h-5 text-destructive flex-shrink-0 mt-0.5" />
                    <p className="text-sm text-destructive">{passwordError}</p>
                  </div>
                )}
              </div>

              {/* Documents Section */}
              <div className="space-y-4">
                <h3 className="font-semibold text-foreground">Upload Documents</h3>
                <p className="text-sm text-muted-foreground">
                  Please upload the required supporting documents (PDF, JPG, PNG)
                </p>

                {[
                  {
                    id: 'transcript',
                    label: 'Academic Transcript',
                    desc: 'Your official academic records',
                  },
                  {
                    id: 'certificate',
                    label: 'O\'Level Certificate',
                    desc: 'WAEC, NECO or equivalent',
                  },
                  {
                    id: 'birth',
                    label: 'Birth Certificate',
                    desc: 'Government-issued birth certificate',
                  },
                ].map((doc) => (
                  <div key={doc.id} className="relative">
                    <input
                      type="file"
                      id={doc.id}
                      accept=".pdf,.jpg,.jpeg,.png"
                      onChange={(e) =>
                        handleFileUpload(doc.id, e.target.files?.[0] || null)
                      }
                      className="hidden"
                    />
                    <label
                      htmlFor={doc.id}
                      className="border-2 border-dashed border-border rounded-lg p-6 hover:border-primary transition cursor-pointer block"
                    >
                      <div className="flex items-center justify-between">
                        <div>
                          <p className="font-semibold text-foreground">
                            {doc.label}
                          </p>
                          <p className="text-sm text-muted-foreground">
                            {doc.desc}
                          </p>
                          {uploadedFiles[doc.id as keyof typeof uploadedFiles] && (
                            <p className="text-xs text-green-600 mt-2">
                              ✓{' '}
                              {
                                (
                                  uploadedFiles[
                                    doc.id as keyof typeof uploadedFiles
                                  ] as File
                                ).name
                              }
                            </p>
                          )}
                        </div>
                        {uploaded[doc.id as keyof typeof uploaded] ? (
                          <CheckCircle className="w-8 h-8 text-green-600 flex-shrink-0" />
                        ) : (
                          <Upload className="w-8 h-8 text-primary flex-shrink-0" />
                        )}
                      </div>
                    </label>
                  </div>
                ))}
              </div>

              <div className="bg-primary/10 border border-primary/20 rounded-lg p-4 flex gap-3">
                <AlertCircle className="w-5 h-5 text-primary flex-shrink-0 mt-0.5" />
                <p className="text-sm text-primary">
                  Application fee: <span className="font-bold">₦2,500</span> - You
                  will be prompted to pay after submitting this form
                </p>
              </div>

              <div className="flex gap-4 pt-6">
                <Button
                  variant="outline"
                  onClick={() => setStep(2)}
                  className="flex-1"
                >
                  Back
                </Button>
                <Button
                  onClick={handleSubmit}
                  loading={loading}
                  className="flex-1"
                  disabled={!formData.password || !formData.confirmPassword}
                >
                  {loading ? 'Submitting & Creating Account...' : 'Submit Application'}
                </Button>
              </div>
            </div>
          )}

          {step === 4 && (
            <div className="text-center py-12">
              <div className="mb-6 flex justify-center">
                <div className="w-16 h-16 rounded-full bg-green-600/20 flex items-center justify-center">
                  <CheckCircle className="w-10 h-10 text-green-600" />
                </div>
              </div>
              <h2 className="text-3xl font-bold mb-3 text-foreground">
                Application Submitted Successfully!
              </h2>
              <p className="text-lg text-muted-foreground mb-6">
                Your account has been created and you can now access the student portal.
              </p>

              <div className="bg-secondary/10 border border-secondary/20 rounded-lg p-6 mb-8 text-left space-y-4">
                <div>
                  <h3 className="font-bold text-foreground mb-2">Your Application ID</h3>
                  <p className="text-sm text-muted-foreground">
                    Save this for your records
                  </p>
                  <div className="mt-2 bg-background rounded-lg p-4 border border-border">
                    <code className="font-mono font-bold text-primary text-lg">
                      {applicationId}
                    </code>
                  </div>
                </div>

                <div className="border-t border-secondary/30 pt-4">
                  <h3 className="font-bold text-foreground mb-2">Login Credentials</h3>
                  <p className="text-sm text-muted-foreground mb-3">
                    Use these to access your student dashboard
                  </p>
                  <div className="space-y-2">
                    <div className="bg-background rounded-lg p-3 border border-border text-left">
                      <p className="text-xs text-muted-foreground mb-1">Email Address</p>
                      <p className="font-mono text-sm font-semibold text-foreground">
                        {formData.email}
                      </p>
                    </div>
                    <div className="bg-background rounded-lg p-3 border border-border text-left">
                      <p className="text-xs text-muted-foreground mb-1">
                        Password (keep this safe!)
                      </p>
                      <p className="font-mono text-sm font-semibold text-foreground">
                        {studentPassword}
                      </p>
                    </div>
                  </div>
                </div>
              </div>

              <div className="bg-accent/10 border border-accent/20 rounded-lg p-6 mb-8 text-left">
                <h3 className="font-bold text-foreground mb-3 flex items-center gap-2">
                  <CheckCircle className="w-5 h-5 text-green-600" />
                  Student Dashboard Created
                </h3>
                <p className="text-sm text-muted-foreground mb-3">
                  Your dashboard is now ready! You can:
                </p>
                <ul className="space-y-2 text-muted-foreground text-sm list-disc list-inside">
                  <li>View your application status</li>
                  <li>Upload additional documents if needed</li>
                  <li>Track your admission progress</li>
                  <li>Complete payment of application fee (₦2,500)</li>
                  <li>Receive notifications about your application</li>
                </ul>
              </div>

              <div className="bg-primary/10 border border-primary/20 rounded-lg p-4 mb-8 flex gap-3">
                <AlertCircle className="w-5 h-5 text-primary flex-shrink-0 mt-0.5" />
                <div className="text-left">
                  <p className="text-sm font-semibold text-primary mb-1">
                    Next Steps:
                  </p>
                  <p className="text-sm text-primary">
                    1) Complete your ₦2,500 application fee payment <br />
                    2) Wait for screening (2-3 weeks) <br />
                    3) Check portal for admission letter
                  </p>
                </div>
              </div>

              <div className="flex gap-4">
                <Link href="/" className="flex-1">
                  <Button variant="outline" className="w-full bg-transparent">
                    Back to Home
                  </Button>
                </Link>
                <Link href="/auth/login" className="flex-1">
                  <Button className="w-full">Access Student Dashboard</Button>
                </Link>
              </div>
            </div>
          )}
        </Card>
      </div>
    </div>
  );
}
