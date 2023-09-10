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
    /* const api_urlRef = useRef('http://localhost:8000/api/v1'); */
    const api_urlRef = useRef('https://rolling-dices-api.fly.dev/api/v1');
    
    
    return (
        <AuthContext.Provider value ={[api_urlRef, isLoggedIn, setIsLoggedIn, user, setUser, roleRef, tokenRef, gamesList, setGamesList, 
                                        winsRate, setWinsRate, playersList, setPlayersList, avgWinsRate, setAvgWinsRate,
                                        ranking, setRanking,refresh, setRefresh]}>
            {props.children}
        </AuthContext.Provider>
    );
}

export { AuthProvider };
export default AuthContext;