<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get employer IDs
        $employers = User::where('role', 'employer')->pluck('id')->toArray();

        if (empty($employers)) {
            $this->command->info('No employers found. Please run UserSeeder first.');
            return;
        }

        $jobs = [
            // Tech Solutions Inc. Jobs
            [
                'title' => 'Senior Laravel Developer',
                'description' => 'We are looking for an experienced Laravel developer to join our team. You will be responsible for developing and maintaining web applications using Laravel, working closely with our design and backend teams.

**Responsibilities:**
- Develop new user-facing features using Laravel
- Build reusable code and libraries for future use
- Ensure the technical feasibility of UI/UX designs
- Optimize applications for maximum speed and scalability
- Collaborate with other team members and stakeholders

**Requirements:**
- 3+ years experience with Laravel
- Strong knowledge of PHP, MySQL, and JavaScript
- Experience with RESTful APIs
- Understanding of MVC design patterns
- Familiarity with front-end technologies like Vue.js or React',
                'location' => 'New York, NY (Remote Available)',
                'type' => 'full-time',
                'category' => 'Web Development',
                'salary_min' => 80000,
                'salary_max' => 120000,
                'deadline' => now()->addDays(30),
                'is_approved' => true,
                'employer_id' => $employers[0],
            ],
            [
                'title' => 'Frontend Developer (React)',
                'description' => 'We\'re seeking a talented Frontend Developer with React experience to create responsive and interactive user interfaces for our web applications.

**Responsibilities:**
- Implement responsive UI components using React
- Collaborate with designers to translate mockups into functional interfaces
- Optimize applications for maximum performance
- Write clean, maintainable code
- Participate in code reviews

**Requirements:**
- 2+ years of experience with React
- Proficiency in JavaScript, HTML, and CSS
- Experience with state management (Redux, Context API)
- Knowledge of responsive design principles
- Familiarity with RESTful APIs',
                'location' => 'Boston, MA',
                'type' => 'full-time',
                'category' => 'Frontend Development',
                'salary_min' => 70000,
                'salary_max' => 100000,
                'deadline' => now()->addDays(45),
                'is_approved' => true,
                'employer_id' => $employers[0],
            ],

            // Global Marketing Group Jobs
            [
                'title' => 'Digital Marketing Specialist',
                'description' => 'Join our marketing team to develop and implement digital marketing strategies for our clients.

**Responsibilities:**
- Create and manage digital marketing campaigns
- Analyze campaign performance and optimize for better results
- Manage social media accounts and content
- Develop SEO strategies
- Collaborate with the creative team on content creation

**Requirements:**
- 2+ years of experience in digital marketing
- Knowledge of SEO/SEM principles
- Experience with social media marketing
- Familiarity with marketing analytics tools
- Strong written and verbal communication skills',
                'location' => 'Chicago, IL',
                'type' => 'full-time',
                'category' => 'Marketing',
                'salary_min' => 55000,
                'salary_max' => 75000,
                'deadline' => now()->addDays(20),
                'is_approved' => true,
                'employer_id' => $employers[1],
            ],
            [
                'title' => 'Content Writer (Part-time)',
                'description' => 'We\'re looking for a creative Content Writer to produce engaging content for our clients\' blogs, websites, and social media platforms.

**Responsibilities:**
- Write clear, compelling copy for various digital platforms
- Research industry-related topics
- Edit and proofread content before publication
- Collaborate with marketing and design teams
- Ensure brand consistency across all content

**Requirements:**
- Excellent writing and editing skills
- Experience in content creation for digital platforms
- Knowledge of SEO principles
- Ability to work independently and meet deadlines
- Portfolio of published work',
                'location' => 'Remote',
                'type' => 'part-time',
                'category' => 'Content Creation',
                'salary_min' => 25,
                'salary_max' => 35,
                'deadline' => now()->addDays(15),
                'is_approved' => true,
                'employer_id' => $employers[1],
            ],

            // Healthcare Innovations Jobs
            [
                'title' => 'Healthcare Data Analyst',
                'description' => 'We are seeking a Healthcare Data Analyst to help us transform healthcare data into actionable insights.

**Responsibilities:**
- Analyze complex healthcare datasets
- Create reports and visualizations to communicate findings
- Identify trends and patterns in healthcare data
- Collaborate with clinical and technical teams
- Develop and maintain databases

**Requirements:**
- Bachelor\'s degree in statistics, data science, or related field
- 2+ years of experience in data analysis
- Proficiency in SQL and data visualization tools
- Knowledge of healthcare systems and terminology
- Strong analytical and problem-solving skills',
                'location' => 'San Francisco, CA',
                'type' => 'full-time',
                'category' => 'Data Analysis',
                'salary_min' => 75000,
                'salary_max' => 95000,
                'deadline' => now()->addDays(25),
                'is_approved' => true,
                'employer_id' => $employers[2],
            ],
            [
                'title' => 'UI/UX Designer for Healthcare Applications',
                'description' => 'Join our team to design intuitive and accessible user interfaces for healthcare applications.

**Responsibilities:**
- Create wireframes, prototypes, and user flows
- Conduct user research and usability testing
- Collaborate with developers to implement designs
- Ensure designs meet accessibility standards
- Stay updated on UX trends and best practices

**Requirements:**
- 3+ years of experience in UI/UX design
- Proficiency in design tools (Figma, Sketch, Adobe XD)
- Portfolio demonstrating healthcare or similar complex domain work
- Understanding of accessibility guidelines
- Experience with user research methodologies',
                'location' => 'Austin, TX (Hybrid)',
                'type' => 'full-time',
                'category' => 'Design',
                'salary_min' => 80000,
                'salary_max' => 110000,
                'deadline' => now()->addDays(40),
                'is_approved' => true,
                'employer_id' => $employers[2],
            ],

            // Pending Approval Jobs
            [
                'title' => 'DevOps Engineer',
                'description' => 'We\'re looking for a DevOps Engineer to help us build and maintain our cloud infrastructure.

**Responsibilities:**
- Implement and manage CI/CD pipelines
- Automate deployment processes
- Monitor system performance and troubleshoot issues
- Manage cloud infrastructure (AWS/Azure)
- Collaborate with development teams

**Requirements:**
- 3+ years of experience in DevOps
- Proficiency with cloud platforms (AWS/Azure)
- Experience with containerization (Docker, Kubernetes)
- Knowledge of infrastructure as code (Terraform, CloudFormation)
- Strong scripting skills (Bash, Python)',
                'location' => 'Seattle, WA',
                'type' => 'full-time',
                'category' => 'DevOps',
                'salary_min' => 90000,
                'salary_max' => 130000,
                'deadline' => now()->addDays(35),
                'is_approved' => false,
                'employer_id' => $employers[0],
            ],
            [
                'title' => 'Social Media Intern',
                'description' => 'Join our marketing team as a Social Media Intern to help manage our clients\' social media presence.

**Responsibilities:**
- Assist in creating and scheduling social media content
- Monitor social media channels and engage with followers
- Track social media metrics and prepare reports
- Research industry trends and competitor activities
- Support the marketing team with various tasks

**Requirements:**
- Currently pursuing a degree in Marketing, Communications, or related field
- Familiarity with major social media platforms
- Strong written communication skills
- Basic knowledge of graphic design tools is a plus
- Enthusiasm for learning and growth',
                'location' => 'Miami, FL',
                'type' => 'internship',
                'category' => 'Marketing',
                'salary_min' => 15,
                'salary_max' => 20,
                'deadline' => now()->addDays(10),
                'is_approved' => false,
                'employer_id' => $employers[1],
            ],
        ];

        foreach ($jobs as $job) {
            // Make sure we're using the correct table
            DB::table('job_posts')->insert(array_merge($job, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
