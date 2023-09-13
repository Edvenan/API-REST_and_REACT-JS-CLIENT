import { Routes, Route } from 'react-router-dom';
import Header from './components/Header';
import Footer from './components/Footer';
import NotFound from './components/NotFound';
import PlayerPage from './components/playerPage/PlayerPage';
import AdminPage from './components/adminPage/AdminPage';
import HomePage from './components/HomePage';
import { useContext, useEffect } from 'react';
import AuthContext from './services/AuthContext';

export default function App() {

    const [,, setIsLoggedIn,, setUser, roleRef, tokenRef] = useContext(AuthContext);

    // Recover user data after page reload to keep session alive
    const loggedInUser = sessionStorage.getItem("user");
    
    useEffect(()=>{
        // If there is an active user, recover user variables
        if (loggedInUser) {
            const foundUser = JSON.parse(loggedInUser);
            setUser(foundUser);
            const foundToken = sessionStorage.getItem("token");
            tokenRef.current = JSON.parse(foundToken);
            const foundRole = sessionStorage.getItem("role");
            roleRef.current = JSON.parse(foundRole);
            setIsLoggedIn(true);
        }
    },[]);
    
    return (
        <div className="min-h-screen flex flex-col bg-black">
            <Header />
            <div className='flex flex-grow'>
                <div className="flex flex-col w-full">
                    <Routes>
                        <Route  path="/"  element={<HomePage />} />
                        <Route  path="/home"  element={<HomePage />} />
                        <Route  path="/player"  element={<PlayerPage />} />
                        <Route  path="/admin"  element={<AdminPage />} />
                        <Route  path="*"  element={<NotFound />} />
                    </Routes>
                </div>
            </div>
            <Footer />
        </div>
    );
  }