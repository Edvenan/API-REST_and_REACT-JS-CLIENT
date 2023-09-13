import { useContext, useEffect, useRef } from "react";
import AuthContext from "../../services/AuthContext";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'

import { toast } from "react-toastify";
import axios from "axios";
import foto from './../../images/green-background.jpg';

function GameRow({ game , num }) {

    // Format date to dd-mm-yy hh:mm
    const dateString = game.created_at;
    const dateObject = new Date(dateString);
    const formattedDate = dateObject.toLocaleString('en-GB', {
        day: '2-digit',
        month: '2-digit',
        year: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    });

    const result = game.result === 1 ? 
        <span className="font-bold" style={{ color: 'greenyellow' }}> WON </span> : <span className="font-bold" style={{ color: 'red' }}> LOST </span>;

    return (
        <tr className="text-center text-white text-[8px] sm:text-xs border-b-2 border-gray-200 bg-black bg-opacity-30">
            <td>{num}</td>
            <td>{formattedDate}</td>
            <td>{game.dice_1}</td>
            <td>{game.dice_2}</td>
            <td>{result}</td>
        </tr>
    );
}

function PlayerGames({id, player, setPlayer}) {

    const [api_urlRef,,,,,, tokenRef, gamesList, setGamesList, 
        , setWinsRate, playersList,,,,,,refresh] = useContext(AuthContext);
    const config = { headers: { Authorization: `Bearer ${tokenRef.current}` } };
    const URL = api_urlRef.current;
    const rows = [];
    const row_player = useRef('');
    let num = 0;

    gamesList.forEach((game) => {  
        num = num + 1;
        rows.push( <GameRow game={game} num={num} key={game.id} /> );
    });
 
    let usr = playersList.find(item => item.id === id);


    useEffect(() => {
        setGamesList(gamesList => []);
        // Load Players Games
        axios.get(`${URL}/players/${id}/games`,config).then(res => {
            row_player.current = res.data.Target_User;
            setGamesList(gamesList => res.data.Target_User.games? res.data.Target_User.games: ['nf']);
            setWinsRate(winsRate => res.data.WinsRate);
        }, (err) => {
            const msg = err.response.data.message;
            toast.error(`Player games could not be loaded - ${msg}`, {theme:"coloured", autoClose: 3000 });
        });
    }, [refresh, id]);

    return (
        <>
        <div className="flex flex-grow bg-black p-1 border-4  border-yellow-300 my-0.5 ml-0.5 overflow-y-auto
                         bg-no-repeat bg-cover bg-top" style={{  'backgroundImage': `url(${foto})` }}>
            <div className="flex flex-col w-full overflow-y-auto">

                <h1 className="my-2 font-bold text-xs sm:text-xl text-center text-yellow-300 ">{`GAMES PLAYED BY ${usr? usr.name.toUpperCase():'...'}`}</h1> 
                <table className="">
                    <thead >
                    <tr>
                        <th className= "py-1 border-b-4 border-gray-200 bg-gray-500 text-center text-[8px] sm:text-[10px] font-semibold text-gray-100 uppercase tracking-wider">
                            #</th>
                        <th className= "py-1 border-b-4 border-gray-200 bg-gray-500 text-center text-[8px] sm:text-[10px] font-semibold text-gray-100 uppercase tracking-wider">
                            Date</th>
                        <th className= "py-1 border-b-4 border-gray-200 bg-gray-500 text-center text-[8px] sm:text-[10px] font-semibold text-gray-100 uppercase tracking-wider">
                            Dice 1</th>
                        <th className= "py-1 border-b-4 border-gray-200 bg-gray-500 text-center text-[8px] sm:text-[10px] font-semibold text-gray-100 uppercase tracking-wider">
                            Dice 2</th>
                        <th className= "py-1 border-b-4 border-gray-200 bg-gray-500 text-center text-[8px] sm:text-[10px] font-semibold text-gray-100 uppercase tracking-wider">
                            Result</th>
                    </tr>
                    </thead>
                    <tbody>{gamesList[0] === 'nf' || gamesList.length === 0? <></>: rows}
                    </tbody>
                </table>
                {gamesList[0] === 'nf'? <h1 className="text-white text-center">No Games Found</h1> : <></>}
                {gamesList.length === 0? <div className='text-white text-center text-2xl'><FontAwesomeIcon icon="fa-solid fa-spinner" spin /></div> : <></>}

            </div>
        </div>
        </>
    );
}

export default PlayerGames;