import { useContext } from "react";
import AuthContext from "../../services/AuthContext";
import foto from './../../images/green-background.jpg';

const PlayerDetails = () => {
    
    const [,,, user,,,, gamesList] = useContext(AuthContext);

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
    
    const dataFormat = "w-full h-6 my-1 py-1 overflow-hidden  text-[10px] sm:text-xs text-center text-white bg-gray-800 rounded-lg";

    let played = gamesList[0]==='nf'?0:gamesList.length;
    let wins = gamesList.filter(function(game){
      return game.result;
    }).length;
    let lost = played - wins;

    return (

        <div className="flex flex-col w-full items-center justify-between text-xs sm:text-base px-2 border-4
                     border-yellow-300 bg-black my-0.5 mr-0.5 bg-no-repeat bg-cover bg-center" style={{  'backgroundImage': `url(${foto})` }}> 

            <p className="my-1 font-bold text-center text-yellow-300 text-xs sm:text-xl">PLAYER DETAILS</p>
            <p className="w-full my-1  text-center text-white ">Name</p>
            <p className={dataFormat}>{user.name}</p>
            <p className="w-full my-1  text-center text-white ">Email</p>
            <p className={dataFormat}>{user.email}</p>
            <p className="w-full my-1  text-center text-white">Games</p>
            <p className={dataFormat}>P: {played} W: {wins} L: {lost} </p>
            <p className="w-full my-1  text-center text-white">Member since</p>
            <p className={`mb-4 ${dataFormat}`}>{formattedDate}</p>

        </div>
    )
}

export default PlayerDetails;