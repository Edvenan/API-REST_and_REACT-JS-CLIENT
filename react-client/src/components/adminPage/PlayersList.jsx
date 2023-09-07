import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import axios from 'axios';
import { useContext, useEffect, useState } from 'react';
import AuthContext from '../../services/AuthContext';
import { toast } from 'react-toastify';
import Swal from 'sweetalert2'


function PlayerRow({ item, onOption, player, setPlayer, active, setActive}) {

    const [isLoggedIn, setIsLoggedIn, user, setUser, roleRef, tokenRef, gamesList, setGamesList, 
        winsRate, setWinsRate, playersList, setPlayersList, avgWinsRate, setAvgWinsRate,
        ranking, setRanking, refresh, setRefresh] = useContext(AuthContext);

    const config = { headers: { Authorization: `Bearer ${tokenRef.current}` } };

    // Format date to dd-mm-yy hh:mm
    const dateString = item.created_at;
    const dateObject = new Date(dateString);
    const formattedDate = dateObject.toLocaleString('en-GB', {
        day: '2-digit',
        month: '2-digit',
        year: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    });

    const name = item.name;
    const email = item.email;
    const winRate = (item.winsRate*100).toFixed(2);

    function showPlayerGames(item) {
        setActive(active => 'games');
        setPlayer(player => item);
        /* setGamesList(gamesList => []); */
        onOption(["gamesList", item]); 
    }
    function editPlayerName(item) {
            Swal.fire({
                title: "Edit player's name",
                input: 'text',
                inputLabel: 'Player Name',
                inputValue: item.name,
                showCancelButton: true,
                allowEnterKey:false,
                preConfirm: (inputValue) => {
                    // Http request
                    const bodyParameters = {
                        'name': inputValue
                     };
                     const id = toast.loading("Editing user name...");
                     axios.put(`http://localhost:8000/api/v1/players/${item.id}`,bodyParameters, config).then(res => {
                        setRefresh(!refresh);
                        toast.update(id, {render:"User name edited successfully!", type:"success", isLoading: false, autoClose: 2000 });
                    }, (err) => {
                        toast.update(id, {render: "User name could not be edited!", type:"error", isLoading: false, autoClose: 3000 });
                    });
                }
            });         
    }
    function deletePlayerGames(item) {

        Swal.fire({
            title: `Delete all ${item.name}'s games \n Are you sure?`,
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete games!',
            allowEnterKey:false,
          }).then((result) => {
            if (result.isConfirmed) {
                // delete games
                 const id = toast.loading(`Deleting ${item.name}'s games...`);
                 axios.delete(`http://localhost:8000/api/v1/players/${item.id}/games`,config).then(res => {
                    setRefresh(!refresh);
                    toast.update(id, {render:`${item.name}'s games deleted successfully!`, type:"success", isLoading: false, autoClose: 2000 });
                }, (err) => {
                    toast.update(id, {render: `${item.name}'s games could not be deleted!`, type:"error", isLoading: false, autoClose: 3000 });
                });
            }
          })

    }
    function deletePlayer(item) {

        Swal.fire({
            title: `Delete player ${item.name} \n Are you sure?`,
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: `Yes, delete player ${item.name}!`,
            allowEnterKey:false,
          }).then((result) => {
            if (result.isConfirmed) {
                // Delete player
                 const id = toast.loading(`Deleting player ${item.name}...`);
                 axios.delete(`http://localhost:8000/api/v1/players/${item.id}`,config).then(res => {
                    if (active = "games" && player && player.id === item.id) onOption("ranking");
                    setRefresh(!refresh);
                    toast.update(id, {render:`Player ${item.name} deleted successfully!`, type:"success", isLoading: false, autoClose: 2000 });
                }, (err) => {
                    const msg = err.response.data.message;
                    toast.update(id, {render: `Player ${item.name}' could not be deleted! ${msg}`, type:"error", isLoading: false, autoClose: 3000 });
                });
            }
          })

    }

    return (
        <tr className="text-center text-lime-400 text-[8px] sm:text-xs border-b-2 border-gray-200 cursor-pointer
                         hover:bg-gray-400 hover:text-black hover:afont-bold bg-black bg-opacity-50"
                         >
            
            <td className="text-left" onClick={()=> showPlayerGames(item)}>{name}</td>
            <td onClick={()=> showPlayerGames(item)}>{email}</td>
            <td onClick={()=> showPlayerGames(item)}>{formattedDate}</td>
            <td className="text-right pr-4" onClick={()=> showPlayerGames(item)}>{winRate}%</td>
            <td>
                {/* Buttons */}
                <div className="flex mb-0 w-full justify-between px-2">
                    <button className="btn btn-blue mr-auto text-pink-500" title="Delete Games"
                        onClick={()=> deletePlayerGames(item)}>
                    <FontAwesomeIcon icon={['fa-solid', `fa-trash`]}/>
                    </button>
                    <button className="btn btn-blue m-auto text-blue-500" title="Edit Name" 
                        onClick={()=> editPlayerName(item)}>
                        <FontAwesomeIcon icon={['fas', `fa-edit`]}/>
                    </button>
                    <button className="btn btn-red ml-auto text-red-500" title="Delete Player"
                        onClick={()=> deletePlayer(item)}>
                    <FontAwesomeIcon icon="fa-solid fa-xmark" size="xl"/>
                    </button>
                </div>

            </td>
        </tr>
    );
}

function PlayersList({onOption, player, setPlayer, active, setActive}) {
    const [isLoggedIn, setIsLoggedIn, user, setUser, roleRef, tokenRef, gamesList, setGamesList, 
        winsRate, setWinsRate, playersList, setPlayersList, avgWinsRate, setAvgWinsRate,
        ranking, setRanking,refresh] = useContext(AuthContext);
    const config = { headers: { Authorization: `Bearer ${tokenRef.current}` } };

    const rows = [];

    playersList.forEach((item) => {
      rows.push(
        <PlayerRow  key={item.id} item={item} onOption={onOption} player={player} setPlayer={setPlayer} active={active} setActive={setActive}/>
      );
    });
  

    useEffect(() => {
        setPlayersList(playersList =>[]);
        // Get list of Players and respective Wins Rate
        axios.get(`http://localhost:8000/api/v1/players`,config).then(res => {
            
            setPlayersList(playersList => res.data.players && res.data.players.length>0? res.data.players: ['nf']);
            setAvgWinsRate(avgWinsRate => res.data.avg_winsRate);
        }, (err) => {
            toast.error("Players list could not be loaded!", {theme:"coloured", autoClose: 3000 });        });
    }, [refresh]);

    return (
        <>
        <div className='flex flex-grow  bg-black p-1 px-2 text-xs border-4 border-yellow-300 
                            my-0.5 ml-0.5 overflow-y-auto min-h-[calc((100vh-124px)/2.5)] max-h-[calc((100vh-124px)/2.5)]
                            '> 
            <div className="flex flex-col w-full h-full">

                <h1 className="my-2 font-bold text-xs sm:text-xl text-center text-yellow-300">PLAYERS LIST</h1>
                
                <table>
                    <thead >
                    <tr>
                        <th className= "py-1 pl-1 border-b-4 border-gray-200 bg-gray-500 text-left text-[8px] sm:text-[10px] font-semibold text-gray-100 uppercase tracking-wider">
                            Name</th>
                        <th className= "py-1 border-b-4 border-gray-200 bg-gray-500 text-center text-[8px] sm:text-[10px] font-semibold text-gray-100 uppercase tracking-wider">
                            Email</th>
                        <th className= "py-1 border-b-4 border-gray-200 bg-gray-500 text-center text-[8px] sm:text-[10px] font-semibold text-gray-100 uppercase tracking-wider">
                            Since</th>
                        <th className= "py-1 pr-4 border-b-4 border-gray-200 bg-gray-500 text-right text-[8px] sm:text-[10px] font-semibold text-gray-100 uppercase tracking-wider">
                            Wins Rate</th>
                            <th className= "py-1 border-b-4 border-gray-200 bg-gray-500 text-center text-[8px] sm:text-[10px] font-semibold text-gray-100 uppercase tracking-wider">
                        </th>
                    </tr>
                    </thead>
                    <tbody>{playersList[0] === 'nf' || playersList.length === 0? <></>: rows}</tbody>
                </table>
                {playersList[0] === 'nf'? <h1 className="text-white text-center">No Players Found</h1> : <></>}
                {playersList.length === 0? <div className='text-white text-center text-2xl'><FontAwesomeIcon icon="fa-solid fa-spinner" spin /></div> : <></>}

            </div>
        </div>
        </>
    );
  }

export default PlayersList;