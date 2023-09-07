import { useContext } from "react";
import AuthContext from "../../services/AuthContext";
import foto from './../../images/green-background.jpg';

const AdminDetails = () => {
    const [isLoggedIn, setIsLoggedIn, user] = useContext(AuthContext);

    // Format date to dd-mm-yy hh:mm
    const dateString = user.created_at;
    const dateObject = new Date(dateString);
    const formattedDate = dateObject.toLocaleString('en-GB', {
        day: '2-digit',
        month: '2-digit',
        year: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    });
    
    return (

        <div className="flex flex-col w-full items-center justify-between text-xs sm:text-base bg-black px-2 border-4
                      border-yellow-300 my-0.5 mr-0.5 bg-no-repeat bg-cover bg-center" style={{  'backgroundImage': `url(${foto})` }}> 

            <p className="my-1 font-bold text-center text-yellow-300 text-xs sm:text-xl">ADMIN DETAILS</p>
            <p className="w-full my-1  text-center text-white ">Name</p>
            <p className="w-full my-1 h-6 text-center py-1 text-[8px] overflow-hidden sm:text-xs text-white bg-gray-800 rounded-lg" >{user.name}</p>
            <p className="w-full my-1  text-center text-white">Email</p>
            <p className="w-full my-1 h-6 text-center py-1 text-[8px] overflow-hidden sm:text-xs text-white bg-gray-800 rounded-lg" >{user.email}</p>
            <p className="w-full my-1  text-center text-white">Member since</p>
            <p className="w-full my-1 mb-4 h-6 text-center py-1 text-[8px] overflow-hidden sm:text-xs text-white bg-gray-800 rounded-lg" >{formattedDate}</p>

        </div>
    )
}

export default AdminDetails;