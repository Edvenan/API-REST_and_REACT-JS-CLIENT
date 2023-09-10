
import { Routes, Route, useNavigate } from 'react-router-dom';
import Header from './components/Header';
import Footer from './components/Footer';
import NotFound from './components/NotFound';
import PlayerPage from './components/playerPage/PlayerPage';
import AdminPage from './components/adminPage/AdminPage';
import HomePage from './components/HomePage';
import { useContext, useEffect } from 'react';
import AuthContext from './services/AuthContext';

export default function App() {

    const navigate = useNavigate();
    const [,, setIsLoggedIn,, setUser, roleRef, tokenRef] = useContext(AuthContext);

    useEffect(()=> {
        // Recover user data after page reload to keep session alive
        const loggedInUser = sessionStorage.getItem("user");
        if (loggedInUser) {
            const foundUser = JSON.parse(loggedInUser);
            setUser(foundUser);
            const foundToken = sessionStorage.getItem("token");
            tokenRef.current = JSON.parse(foundToken);
            const foundRole = sessionStorage.getItem("role");
            roleRef.current = JSON.parse(foundRole);
            setIsLoggedIn(true);
            if (roleRef.current === 'player'){
                navigate("/player");
            }else if (roleRef.current === 'admin'){
                navigate("/admin");
            }
        }
        else {
            navigate("/");
        }
    }, []);

    return (
        <div className="min-h-screen flex flex-col bg-black">
            <Header />
            <div className='flex flex-grow'>
                <div className="flex flex-col w-full">
                    <Routes>
                        <Route exact path="/"  element={<HomePage />} />
                        <Route exact path="/home"  element={<HomePage />} />
                        <Route exact path="/player"  element={<PlayerPage />} />
                        <Route exact path="/admin"  element={<AdminPage />} />
                        <Route exact path="*"  element={<NotFound />} />
                    </Routes>
                </div>
            </div>
            <Footer />
        </div>

    );
  }