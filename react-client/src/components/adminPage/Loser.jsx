import axios from 'axios';
import { useContext, useEffect, useRef, useState } from 'react';
import AuthContext from '../../services/AuthContext';
import { toast } from 'react-toastify';
import foto from './../../images/green-background.jpg';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'

function PlayerRow({ player, rank }) {

    const name = player.name;
    const winsRate = (player.winsRate*100).toFixed(2);

    return (
        <tr className="text-center text-white text-[8px] sm:text-xs border-b-2 border-gray-200">
            <td className="text-center">{rank}</td>
            <td className="text-left pl-4">{name}</td>
            <td className="text-right">{winsRate}% &emsp;&emsp; </td>
        </tr>
    );
}

function Loser({onOption, setPlayer}) {
    const [isLoggedIn, setIsLoggedIn, user, setUser, roleRef, tokenRef, gamesList, setGamesList, 
        winsRate, setWinsRate, playersList, setPlayersList, avgWinsRate, setAvgWinsRate,
        ranking, setRanking, refresh] = useContext(AuthContext);
    const config = { headers: { Authorization: `Bearer ${tokenRef.current}` } };
    const [loser, setLoser] =useState([]);


    const rows = [];
    loser.forEach((player) => {
        rows.push( <PlayerRow key={player.id} player={player} rank={player.rank} /> );
    });

    useEffect(() => {
        setLoser(loser => []);
        // Get Loser
        axios.get(`http://localhost:8000/api/v1/players/ranking/loser`,config).then(res => {
            setLoser(loser => res.data.loser && (res.data.loser).length>0? res.data.loser: ['nf']);
        }, (err) => {
            toast.error("Ranking could not be loaded!", {theme:"coloured", autoClose: 3000 });
        });
    }, [refresh]);

    return (
        <>
        <div className='flex flex-grow  bg-black p-1 px-2 text-xs border-4 border-yellow-300 
                            my-0.5 ml-0.5 shadow-lg overflow-hidden justify-center bg-no-repeat bg-cover bg-top' style={{  'backgroundImage': `url(${foto})` }}> 
            <div className="flex flex-col h-full ">

                <h1 className="my-2 font-bold text-xs sm:text-xl text-center text-yellow-300">LOSER</h1>
                
                <table>
                    <thead >
                    <tr>
                        <th className= "py-1 px-4 border-b-4 border-gray-200 bg-gray-500 text-left text-[8px] sm:text-[10px] font-semibold text-gray-100 uppercase tracking-wider">
                            Rank</th>
                        <th className= "py-1  px-4  border-b-4 border-gray-200 bg-gray-500 text-left text-[8px] sm:text-[10px] font-semibold text-gray-100 uppercase tracking-wider">
                            Name</th>
                        <th className= "py-1  px-4 border-b-4 border-gray-200 bg-gray-500 text-center text-[8px] sm:text-[10px] font-semibold text-gray-100 uppercase tracking-wider">
                            Wins Rate</th>
                            <th className= "py-1 border-b-4 border-gray-200 bg-gray-500 text-center text-[8px] sm:text-[10px] font-semibold text-gray-100 uppercase tracking-wider">
                        </th>
                    </tr>
                    </thead>
                    <tbody>{loser[0] === 'nf' || loser.length === 0? <></>: rows}</tbody>
                </table>
                {loser[0] === 'nf'? <h1 className="text-white text-center">No Players Found</h1> : <></>}
                {loser.length === 0? <div className='text-white text-center text-2xl'><FontAwesomeIcon icon="fa-solid fa-spinner" spin /></div>  : <></>}

            </div>
        </div>
        </>
    );
  }

export default Loser;