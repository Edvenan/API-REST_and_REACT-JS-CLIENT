import { useContext, useEffect } from 'react';
import AuthContext from '../services/AuthContext';
import SlideShow from './SlideShow';
import { useNavigate } from 'react-router-dom';


export default function HomePage() {

    const navigate = useNavigate();
    const [,isLoggedIn,,,, roleRef] = useContext(AuthContext);

    useEffect(() => {
        if (isLoggedIn){
            if (roleRef.current === 'player'){
                navigate("/player");
            }else if (roleRef.current === 'admin'){
                navigate("/admin");
            }
        }

    }, []);

    return (

            <div className='flex flex-grow'>
                <div className="flex flex-col w-full">
                    <SlideShow />
                </div>
            </div>

    );
}