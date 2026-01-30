import { NextRequest, NextResponse } from 'next/server';

/**
 * POST /api/auth/register
 * Register a new student account during admission process
 * 
 * In production, this would:
 * 1. Validate input
 * 2. Check if email already exists
 * 3. Hash password with bcrypt
 * 4. Create user in database
 * 5. Create student profile
 * 6. Return user data with auth token
 */
export async function POST(request: NextRequest) {
  try {
    const body = await request.json();
    const {
      studentId,
      name,
      email,
      password,
      phone,
      dateOfBirth,
      program,
      role = 'student',
      status = 'APPLICANT',
    } = body;

    // Validation
    if (!name || !email || !password || !studentId) {
      return NextResponse.json(
        { error: 'Missing required fields' },
        { status: 400 }
      );
    }

    if (password.length < 8) {
      return NextResponse.json(
        { error: 'Password must be at least 8 characters' },
        { status: 400 }
      );
    }

    // In production: Hash password with bcrypt
    // const hashedPassword = await bcrypt.hash(password, 10);

    // In production: Save to database
    // const user = await db.user.create({
    //   studentId,
    //   name,
    //   email,
    //   password: hashedPassword,
    //   phone,
    //   dateOfBirth,
    //   program,
    //   role,
    //   status,
    //   createdAt: new Date(),
    // });

    console.log('[v0] Student account created:', {
      studentId,
      email,
      name,
      program,
      status,
    });

    // Return success response with user data (without password)
    return NextResponse.json(
      {
        success: true,
        message: 'Student account created successfully',
        data: {
          studentId,
          name,
          email,
          program,
          role,
          status,
          createdAt: new Date().toISOString(),
        },
      },
      { status: 201 }
    );
  } catch (error) {
    console.error('[v0] Registration failed:', error);
    return NextResponse.json(
      { error: 'Failed to create account' },
      { status: 500 }
    );
  }
}
