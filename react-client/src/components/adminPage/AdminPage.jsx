import { useState, useContext, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import AuthContext from "../../services/AuthContext";
import 'react-toastify/dist/ReactToastify.css';

import AdminOptions from './AdminOptions';
import AdminDetails from './AdminDetails';

import PlayersList from './PlayersList';
import AverageScore from './AverageScore';
import Loser from './Loser';
import Winner from './Winner';
import Ranking from './Ranking';
import PlayerGames from './PlayerGames';


export default function AdminPage() {

    const [player, setPlayer] = useState(null);
    const [option, setOption] = useState(<Ranking/>);


    const [isLoggedIn, setIsLoggedIn, user, setUser, roleRef, tokenRef, gamesList, setGamesList, 
        winsRate, setWinsRate, playersList, setPlayersList, avgWinsRate, setAvgWinsRate,
        ranking, setRanking, refresh, setRefresh] = useContext(AuthContext);
    const navigate = useNavigate();
    const [active, setActive] = useState('king');


    //runs only once after the page is first rendered
    useEffect(() => {
        if (!isLoggedIn){
            navigate("/");
        }
    }, []);

    function handleOption(chosenOption){

        if (chosenOption === "refresh"){
            /* setOption(<Ranking />); */
            setRefresh(refresh => !refresh);
        }
        else if (chosenOption === "ranking"){
            setOption(<Ranking />);
        }
        else if (chosenOption === "winner"){
            setOption(<Winner/>);
        }
        else if (chosenOption === "loser"){
            setOption(<Loser />);
        }
        else if (chosenOption[0] === "gamesList"){
            setOption(<PlayerGames  setPlayer={setPlayer} id={chosenOption[1].id}/>);
        }
        
    };

    return (
        <> {isLoggedIn?
            <div className='flex flex-grow '>
                <div className="flex flex-col w-1/6 items-center justify-start">
                    <AdminDetails/>
                    <AdminOptions onOption={handleOption} active={active} setActive={setActive}/>
                </div>
                <div className='flex flex-grow w-5/6'>
                    <div className='flex flex-col w-full 
                                    max-h-[calc(100vh-124px)]   sm:max-h-[calc(100vh-136px)]        
                                    min-h-[582px]               sm:min-h-[650px]             md:min-h-[650px] lg:min-h-[620px]">'>
                        <AverageScore />  
                        <PlayersList onOption={handleOption} player={player} setPlayer={setPlayer} active={active} setActive={setActive}/>
                        {option}
                    </div>
                </div>
            </div>
            : 
            <div>Rerouting....</div>}
        </>
    );
  }