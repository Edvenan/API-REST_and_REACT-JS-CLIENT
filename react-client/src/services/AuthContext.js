import { createContext, useRef, useState } from "react";

const AuthContext = createContext();

function AuthProvider(props){

    const [isLoggedIn, setIsLoggedIn] = useState(false);
    const [user, setUser] = useState(null);
    const [winsRate, setWinsRate] = useState(0);
    const [gamesList, setGamesList] = useState([]);
    const [playersList, setPlayersList] = useState([]);
    const [avgWinsRate, setAvgWinsRate] = useState(0);
    const [ranking, setRanking] = useState([]);
    const [refresh, setRefresh] = useState(false);
    const tokenRef = useRef (null);
    const roleRef = useRef (null);
    
    
    return (
        <AuthContext.Provider value ={[isLoggedIn, setIsLoggedIn, user, setUser, roleRef, tokenRef, gamesList, setGamesList, 
                                        winsRate, setWinsRate, playersList, setPlayersList, avgWinsRate, setAvgWinsRate,
                                        ranking, setRanking,refresh, setRefresh]}>
            {props.children}
        </AuthContext.Provider>
    );
}

export { AuthProvider };
export default AuthContext;