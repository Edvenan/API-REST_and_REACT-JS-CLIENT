import { useContext } from "react";
import AuthContext from "../../services/AuthContext";
import foto from './../../images/slide2.jpg';

const AverageScore = () => {
    const [,,,,,,,,,,,,, avgWinsRate] = useContext(AuthContext);

    return (

        <div className="py-4 font-bold text-yellow-300 text-xl my-0.5 ml-0.5 bg-black text-center 
                        border-4 border-yellow-300 bg-no-repeat bg-cover bg-top" style={{  'backgroundImage': `url(${foto})` }}> 
            AVERAGE SCORE : { (avgWinsRate *100).toFixed(2) }%
        </div>
    )
}

export default AverageScore;