// import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/components/ui/card';
import { Avatar, AvatarImage, AvatarFallback } from '@/components/ui/avatar';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Company',
        href: '/company',
    },
];

interface TeamMemberProps {
    image: string;
    name: string;
    role: string;
}

const TeamMember = ({ image, name, role }: TeamMemberProps) => {
    return (
        <Card className="w-54 md:w-64 flex-shrink-0">
            <CardHeader className='flex flex-col items-center'>
                <Avatar className="mb-4 w-28 h-28 md:w-32 md:h-32">
                    <AvatarImage src={image}  />
                    <AvatarFallback>CN</AvatarFallback>
                </Avatar>
            </CardHeader>
            <CardContent>
                <CardTitle className='className="text-2xl md:text-xl mb-2 text-center'>{name}</CardTitle>
                <CardDescription className="text-l md:text-lg text-center">{role}</CardDescription>
            </CardContent>
        </Card>
    );
};

export default function Company() {

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Company" />

            <section className="mb-15 h-38" style={{ backgroundImage: `url('https://picsum.photos/seed/${Math.random()}/1200/400')` }}>
                <div id='profile' className="relative z-10 flex items-center ml-5 mt-22">
                    <img
                        src="/logoSkak.png"
                        alt="Logo"
                        className="h-30 w-30 border-3 border-white rounded-xl shadow-lg"
                    />
                    <div className="ml-4 mt-20 " style={{ paddingRight: '20px' }}>
                        <h1 className="text-2xl font-bold mb-0.5">SKAK STUDIOS</h1>
                        <p className="text-l">IP Development & Production Company</p>
                    </div>
                </div>
            </section>
            <hr className='mt-15 md:mt-10'/>
            <section className='p-6 rounded-lg'>
                <h2 className="text-xl font-bold mb-2">Vision</h2>
                <p className="mb-4">Most Valuable IP Company di Indonesia.</p>
                <h2 className="text-xl font-bold mb-2">Mission</h2>
                <p className="mb-4">
                    Menjadi yang terdepan di industri IP dengan semangat "Lokal menuju
                    Global". Melakukan inovasi dan kolaborasi dengan partner serta talenta
                    terbaik untuk menghasilkan produk terbaik. Memberikan manfaat dan
                    keuntungan sebesar-besarnya kepada stakeholder.
                </p>
                <h2 className="text-xl font-bold mb-2">Value</h2>
                <p className="mb-4">
                    Memberikan kegembiraan, inspirasi, dan semangat positif kepada masyarakat
                    melalui tontonan serta produk kreatif dan inovatif.
                </p>
            </section>
            <section className='p-6  rounded-lg'>
                <h2 className="text-xl font-bold mb-2">Company Structure</h2>
                <div className="flex overflow-x-auto space-x-4 mt-4">
                    <TeamMember
                    image="https://picsum.photos/200/200?random=1"
                    name="Ricky Ramadhan Setiyawan"
                    role="Co-Founder & CEO"
                    />
                    <TeamMember
                    image="https://picsum.photos/200/200?random=2"
                    name="Bayu Eko Moektito"
                    role="Founder"
                    />
                    <TeamMember
                    image="https://picsum.photos/200/200?random=3"
                    name="Henry Myranda"
                    role="Co-Founder & Business Operational"
                    />
                    <TeamMember
                    image="https://picsum.photos/200/200?random=4"
                    name="Achmad Rofiq"
                    role="Co-Founder & Head IP Dev"
                    />
                </div>
                <div className="flex justify-end mt-4">
                    <a href="#" className="text-blue-500 hover:text-blue-700">
                    See All{" "}
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        className="h-5 w-5 inline-block ml-1"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                        fillRule="evenodd"
                        d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                        clipRule="evenodd"
                        />
                    </svg>
                    </a>
                </div>
            </section>
        </AppLayout>
    );
}


