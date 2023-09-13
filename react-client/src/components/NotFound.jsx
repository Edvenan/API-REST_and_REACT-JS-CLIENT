import { useContext, useEffect } from 'react';
import AuthContext from '../services/AuthContext';
import { useNavigate } from 'react-router-dom';


export default function NotFound() {

    const navigate = useNavigate();
    const [,isLoggedIn,,,, roleRef] = useContext(AuthContext);

    function delay(milliseconds){
        return new Promise(resolve => {
            setTimeout(resolve, milliseconds);
        });
    }
    async function redirecting(){
        await delay(8000);
        if (isLoggedIn){
            if (roleRef.current === 'player'){
                navigate("/player");
            }else if (roleRef.current === 'admin'){
                navigate("/admin");
            } 
        } else {
            navigate("/");
        }
    }
    
    useEffect(()=> {
        redirecting();
    }, [])
    return (

            <div className='flex flex-grow'>
                <div className="flex flex-col w-full text-yellow-300 text-center justify-center">
                    <p>404 - Page Not Found</p>
                    <p>Oops! The page you're looking for does not exist</p>
                    <br></br>
                    <p>Redirecting to previous page... (Click 'dices' icon to avoid the wait)</p>
                </div>
            </div>
    );
}