import { useEffect, useContext } from 'react';
import AuthContext from "../../services/AuthContext";
import { useNavigate } from 'react-router-dom';

import PlayerOptions from './PlayerOptions';
import PlayerDetails from './PlayerDetails';
import PlayGame from './PlayGame';
import GamesTable from './GamesTable';
import PlayerScore from './PlayerScore';



function PlayerPage() {

    const [isLoggedIn] = useContext(AuthContext);
    const navigate = useNavigate();

    //runs only once after the page is first rendered
    useEffect(() => {
        if (!isLoggedIn){
            navigate("/");
        }

    });

    return (
        <> {isLoggedIn?
            <div className='flex flex-grow'>
                <div className="flex flex-col w-1/6 items-center justify-start">
                    <PlayerDetails />
                    <PlayerOptions />
                </div>
                <div className='flex flex-col w-3/6 '>
                    <PlayerScore />   
                    <PlayGame/>
                </div>
                <div className="flex flex-col w-2/6">
                    <GamesTable />
                </div>
            </div>
            : 
            <div>Rerouting....</div>}
        </>
    )
  }

  export default PlayerPage;