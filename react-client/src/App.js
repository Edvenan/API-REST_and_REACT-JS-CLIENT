
import { Routes, Route } from 'react-router-dom';
import { useContext } from "react";
import AuthContext from "./services/AuthContext";

import Header from './components/Header';
import Footer from './components/Footer';
import NotFound from './components/NotFound';
import PlayerPage from './components/playerPage/PlayerPage';
import AdminPage from './components/adminPage/AdminPage';
import HomePage from './components/HomePage';

export default function App() {

    const [isLoggedIn, setIsLoggedIn, userRef, roleRef, tokenRef] = useContext(AuthContext);

    
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