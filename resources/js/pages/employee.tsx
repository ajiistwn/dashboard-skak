import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/components/ui/card';
import { Avatar, AvatarImage, AvatarFallback } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select, SelectTrigger, SelectValue, SelectContent, SelectItem } from '@/components/ui/select';
import { Filter } from 'lucide-react';
import { usePage } from '@inertiajs/react';

// import { Icon } from '@/components/icon';


interface TeamMemberProps {
    employee: {
        id: number;
        name: string;
        role: string;
        image: string;
    };
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Employee',
        href: '/employee',
    },
];

export default function Employee() {
    
    const { auth } = usePage().props;
    const currentUser = auth.user;
    const employeesBoard = [
        {
            id: 1,
            name: "Ricky Ramadhani Setiyawan",
            role: "Co-Founder & CEO",
            image: "https://picsum.photos/200/200?random=1",
        },
        {
            id: 2,
            name: "Bayu Eko Moektito",
            role: "Founder",
            image: "https://picsum.photos/200/200?random=2",
        },
        {
            id: 3,
            name: "Henny Myranda",
            role: "Co-Founder & Business Operational",
            image: "https://picsum.photos/200/200?random=3",
        },
        {
            id: 4,
            name: "Achmad Rofiq",
            role: "Co-Founder & Head IP Dev",
            image: "https://picsum.photos/200/200?random=4",
        },
        {
            id: 5,
            name: "I Maheswara Cetta Gantari",
            role: "",
            image: "https://picsum.photos/200/200?random=5",
        },
    ];

    const employeesTeam = [
        {
            id: 6,
            name: "Wahyu Adi Saputra",
            role: "",
            image: "https://picsum.photos/200/200?random=6",
        },
        {
            id: 7,
            name: "Raihan Wahid",
            role: "",
            image: "https://picsum.photos/200/200?random=7",
        },
        {
            id: 8,
            name: "Nurul Hidayati",
            role: "",
            image: "https://picsum.photos/200/200?random=8",
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Employee" />

            <div className="p-4">
                {/* Header */}
                <div className="flex flex-col mb-4">
                {currentUser?.access === 'bod' && (
                    <Button variant="outline" className="bg-purple-600 text-white w-[30%] py-2 px-2 mb-3">
                        + Add Employee
                    </Button>
                )}

                <div className="flex gap-2">
                    <Input placeholder="Search" />
                    <Select>
                        <SelectTrigger className='w-[20%]'>
                            <SelectValue><Filter /> Filter</SelectValue>
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="option-1">Option 1</SelectItem>
                            <SelectItem value="option-2">Option 2</SelectItem>
                        </SelectContent>
                    </Select>
                </div>
            </div>

                {/* Board of Director */}
                <div className="mb-4">
                    <h2 className="text-lg font-semibold mb-2">Board of Director</h2>
                    <div className="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 mt-4">
                        {employeesBoard.map((employee) => (
                            <TeamMember key={employee.id} employee={employee} />
                        ))}
                    </div>
                </div>

                {/* Team */}
                <div>
                    <h2 className="text-lg font-semibold mb-2">Team</h2>
                    <div className="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 mt-4">
                        {employeesTeam.map((employee) => (
                            <TeamMember key={employee.id} employee={employee} />
                        ))}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}

const TeamMember = ({ employee }: TeamMemberProps) => {
    const { image, name, role } = employee;
    return (
        <Card className="p-3 gap-2">
            <CardHeader className="flex flex-col items-center">
                <Avatar className="mb-1 w-28 h-28 md:w-32 md:h-32">
                    <AvatarImage src={image} />
                    <AvatarFallback>CN</AvatarFallback>
                </Avatar>
            </CardHeader>
            <CardContent className="p-0">
                <CardTitle className="text-l md:text-l lg:text-lg mb-2 text-center">{name}</CardTitle>
                <CardDescription className="text-sm md:text-l  text-center">{role}</CardDescription>
            </CardContent>
        </Card>
    );
};
