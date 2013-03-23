/******************************************************************
 * This example shows:
 *	How to parse a verse reference
 *	How to persist a custom range key in a book
 *
 * $Id: swmgr.h 2321 2009-04-13 01:17:00Z scribe $
 *
 * Copyright 1998-2009 CrossWire Bible Society (http://www.crosswire.org)
 *	CrossWire Bible Society
 *	P. O. Box 2528
 *	Tempe, AZ  85280-2528
 *
 * This program is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the
 * Free Software Foundation version 2.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 */

#include <iostream>
#include <swmgr.h>
#include <versekey.h>
#include <listkey.h>
#include <swmodule.h>
#include <markupfiltmgr.h>
#include <sstream>
#include <string>
#include <iomanip>

using sword::SWMgr;
using sword::VerseKey;
using sword::ListKey;
using sword::SWModule;
using sword::SW_POSITION;
using sword::FMT_PLAIN;
using sword::FMT_OSIS;
//FMT_PLAIN, FMT_THML, FMT_GBF, FMT_HTML, FMT_HTMLHREF, FMT_RTF, FMT_OSIS, FMT_WEBIF, FMT_TEI
using sword::FMT_THML;
using sword::FMT_GBF;
using sword::FMT_HTML;
using sword::FMT_HTMLHREF;
using sword::FMT_RTF;
using sword::FMT_WEBIF;
using sword::FMT_TEI;
using sword::MarkupFilterMgr;
using std::cout;
using std::endl;
std::string bible = "KJV";
std::string search = "";
std::string verse = "";
std::string bible_format = "PLAIN";
std::string str_replace(const std::string& search, const std::string& replace, const std::string& subject)
{
    std::string str = subject;
    size_t pos = 0;
    while((pos = str.find(search, pos)) != std::string::npos)
    {
        str.replace(pos, search.length(), replace);
        pos += replace.length();
    }
    return str;
}

std::string clean_text( std::string& dirty ){
	std::string clean = "";
	std::string clean2 = "";
	std::string clean3 = "";
	clean = str_replace( "%20" , " " ,  dirty);
	clean2 = str_replace( "+" , " " ,  clean);
	clean3 = str_replace( "\%3A" , ":" , clean2 ); 
	return clean3;
}

int set_key( std::string purl ){
        std::string token;
        std::string key="";
        std::string value="";
        int p = 0;
        std::istringstream iss(purl);
        while ( getline(iss, token, '=') )
        {
                if( p == 0 ){
                        key = token;
                        p++;
                }
                if( p==1){
                        value = token;
                }
        }

        if( key == "bible" ){
                bible= clean_text( value ); //str_replace( "%20" , " " ,  value);
        }
        if( key == "search" ){
                search= clean_text( value ); //str_replace( "%20" , " " ,  value);
        }
	if( key == "format" ){
		bible_format = clean_text( value ) ; //str_replace( "%20" , " " ,  value);
	}
        return 0;
}

int parce_url( std::string text ){
   std::string token;
   std::istringstream iss(text);
   while ( getline(iss, token, '&') )
   {
        set_key(token);
   }
return 0;
}


std::string convert_string(std::string s) {
    std::stringstream ss;
    for (size_t i = 0; i < s.length(); ++i) {
        if (unsigned(s[i]) < '\x20' || s[i] == '\\' || s[i] == '"') {
            ss << "\\u" << std::setfill('0') << std::setw(4) << std::hex << unsigned(s[i]);
        } else {
            ss << s[i];
        }
    } 
    return ss.str();
}

int main(int argc, char **argv)
{
	const char *range = "John 2:10,12-15";
	char *data = getenv("QUERY_STRING");
	parce_url( data );
	if( search != "" ){
		range = search.c_str();
	}
	VerseKey parser;
	ListKey result;
	//verse = "Testing";
	//bible = "KJV";
	result = parser.ParseVerseList(range, parser, true);
	// let's iterate the key and display
	for (result = TOP; !result.Error(); result++) {
		//cout << result << "\n";
	}
	
	cout << endl;
	//cout << convert_string( data );
	// Now if we'd like persist this key for use inside of a book...
	result.Persist(true);
	
	// Let's get a book;
	//SWMgr library(new MarkupFilterMgr(FMT_PLAIN));
	
	MarkupFilterMgr *mgrflt = new MarkupFilterMgr(FMT_PLAIN);	
	if( bible_format == "OSIS" ){
		mgrflt = new MarkupFilterMgr(FMT_OSIS);
	}
//FMT_PLAIN, FMT_THML, FMT_GBF, FMT_HTML, FMT_HTMLHREF, FMT_RTF, FMT_OSIS, FMT_WEBIF, FMT_TEI
	if( bible_format == "THML" ){
                mgrflt = new MarkupFilterMgr(FMT_THML);
        }
	if( bible_format == "GBF" ){
                mgrflt = new MarkupFilterMgr(FMT_GBF);
        }
        if( bible_format == "HTML" ){
                mgrflt = new MarkupFilterMgr(FMT_HTML);
        }

        if( bible_format == "HTMLHREF" ){
                mgrflt = new MarkupFilterMgr(FMT_HTMLHREF);
        }

        if( bible_format == "RTF" ){
                mgrflt = new MarkupFilterMgr(FMT_RTF);
        }
        if( bible_format == "WEBIF" ){
                mgrflt = new MarkupFilterMgr(FMT_WEBIF);
        }
        if( bible_format == "TEI" ){
                mgrflt = new MarkupFilterMgr(FMT_TEI);
        }

	SWMgr library(mgrflt);
	library.setGlobalOption("Footnotes","Off");
	const char *bblchar = bible.c_str();
	SWModule *book = library.getModule(bblchar);

	
	
	// and set our limited key inside
	book->setKey(result);
	int use = 1;
	cout << "[";
	for ((*book) = TOP; !book->Error(); (*book)++) {
		if( use == 1 ){
			use = 0;
		} else {
			cout << ",";
		}
		cout << "{\"verse\": \"" << convert_string(book->getKeyText()) << "\",\"text\":\"" << convert_string(book->RenderText()) << "\"}\n";
	}
	cout << "]";
	return 0;
}
