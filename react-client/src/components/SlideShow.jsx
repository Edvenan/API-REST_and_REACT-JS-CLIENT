import React from 'react';
import { Slide } from 'react-slideshow-image';
import 'react-slideshow-image/dist/styles.css';
import slide1 from './../images/slide1.jpg';
import slide2 from './../images/slide2.jpg';

const SlideShow = () => {

    const spanStyle = "p-5 mb-10 bg-transparent text-yellow-300 text-3xl md:text-5xl font-serif text-center";
    const divStyle = { display: 'flex', alignItems: 'center', justifyContent: 'center', backgroundSize: 'cover', height: 'calc(100vh - 136px)' }

    const slideImages = [
        {
          url: slide1,
          caption: 'Welcome to Rolling Dices!'
        },
        {
          url: slide2,
          caption: 'Sign in or Register for a new account'
        },
      ];

    return (
        <div className="slide-container border-x-4 border-yellow-300 my-auto bg-no-repeat bg-cover bg-center" style={{  'backgroundImage': `url(${slide1})` }}>
        <Slide arrows={false} duration={1000}>
         {slideImages.map((slideImage, index)=> (
            <div key={index}>
              <div style={{ ...divStyle, /* 'backgroundImage': `url(${slideImage.url})` */ }}>
                <span className={spanStyle}>{slideImage.caption}</span>
              </div>
            </div>
          ))} 
        </Slide>
      </div>

    );
};

export default SlideShow;