import { useContext } from "react";
import AuthContext from "../../services/AuthContext";
import foto from './../../images/green-background.jpg';

const PlayerScore = () => {

    const [isLoggedIn, setIsLoggedIn, user, setUser, roleRef, tokenRef, gamesList, setGamesList, winsRate, setWinsRate] = useContext(AuthContext);

    return (

        <div className="py-4 font-bold text-yellow-300 text-xl my-0.5 bg-black text-center 
                        border-4 border-yellow-300 bg-no-repeat bg-cover bg-center" style={{  'backgroundImage': `url(${foto})` }}> 
            PLAYER SCORE : { (winsRate *100).toFixed(2) }%
        </div>
    )
}

export default PlayerScore;