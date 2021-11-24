## Documentation
This is the documentation for Record Home Dashboard external module.

### Overview

1. [Introduction](#introduction)
2. [Editor Usage](#editor-usage)
3. [Manage Dashboard](#manage-dashboard)
4. [Additional Configuration](#additional-configuration)

### Introduction
Before starting to create Dashboard it is important to know about the basic module design.

#### Editor / Render View
Record Home Dashboard module consists of two main views. The Editor View is used to create dashboards by adding a custom layout on top of the Grid System with applied Element Types. The Render View is embedded on the Record Home page of each record and renders the layout in the defined grid system with applied element types with the record as data source.


#### Grid System
Record Home Dashboard uses a grid system to create the dashboard layout. This is common practise and ensures that content can be structured logically while maintaining readbility and design.

A grid system consists always of rows and columns.
There can be "n" rows and within each row can be "o" columns.
In each column can be further "p" elements.

![image](https://user-images.githubusercontent.com/75415872/143214704-c4514352-e529-435b-a372-623e5d1412ed.png)

*Fig. 2: Above example grid system consists of `n=3` rows, where the first row has `o=3`, the second row `o=2` and the third row `o=1` columns. Elements within columns are not shown to keep things simple.*

#### Element Types
Record Home Dashboard has different element types that can be included into each column. There currently available element types are:

- Text: Includes text with the option to add bold, italic or underlined styles
- Link: Includes a valid link to an external or internal target. Piping of smart variables are supported and allow linking to REDCap internal destinations, such as last-instance of a repeating instrument. Allow to add an icon, choose color, link title and window to be opened (same/new)
- List: Includes a list with `p` list elements where each list element consists of a key-value-pair. Piping of variables is supported
- Table: Includes a table of all entries for a repeating instrument. Filters by columns that can be defined

### Editor Usage
This section explains how the editor can be used to create, edit and manage dashboards. In general any interaction with the dashboard editor canvas saves changes automatically after each step. Any layout or content created is in the dashboard editor`s responsbility. The module itself cannot ensure that every content will be rendered successfully.

#### Rows
The first step of creating a dashboard is to add a row. Rows can be added by clicking "Add Row". A row can be deleted by clicking the trash icon within the row`s header.

![image](https://user-images.githubusercontent.com/75415872/143234249-16bb5996-1e29-49e4-8dc7-e8989686ebe9.png)

#### Columns
After creating a row there can be added columns inside a row by clicking the plus icon within the row's header. There is a maximum of 6 columns per row to keep editor and rendered dashboard's clean and maintainable. A column can be deleted by clicking on the trash icon within the column's header.

![image](https://user-images.githubusercontent.com/75415872/143238476-29523596-2fcb-44f2-bdfd-cf5f9550cc99.png)


#### Elements
Within each column there can be added multiple element types. An element can be added by clicking the plus within the column's header. After click a element modal will open up. Within the modal there can be selected in a tab menu which type of element should be created. By default on first use the tab "Text" will be active. For each element type there will be different inputs and options available.

##### Text
Renders a headline.<br />
*Title*: text input to be rendered as headline<br />
*Decoration*: multiple-check option for bold, italic and underlined text styles

![image](https://user-images.githubusercontent.com/75415872/143238611-a6b82fc3-632b-45dc-acf3-a380b577eea2.png)


##### Link
Renders a button with hyperlink.<br />
*Title:* text input to be rendered as link text<br />
*URL:* text input to be rendered as url. Supports piping with smart variables and labels.<br />
*Icon:* text input to be rendered as icon using Font-Awesome library. Click on the search icon to browser available icons an their string representation.<br />
*Variant:* drop-down select to render button style/color based on Bootstrap Variants.<br />
*Target:* drop-down select to render link target to open in same or new window

![image](https://user-images.githubusercontent.com/75415872/143240229-3fdb8e24-e559-4c32-929c-911b25aca31a.png)


##### List
Renders a list of multiple list elements. A list element can be added by clicking on the "Add" button below the last editable list element.<br />
*title*: text input that renders the list element's title.<br />
*value*: text input that is rendered to lsit element's value. Piping of smart variables and lables is supported.<br \>
List elements whose value is empty will be omitted and not saved to the database. The title can be left empty.

![image](https://user-images.githubusercontent.com/75415872/143240354-bb396a97-fa01-4b95-9c03-6ac77b7d4fc4.png)


##### Table
Renders a paginable table from repeating instrument data. Repeating instruments have to be enable through REDCap project settings and to be added in the project designer/dictionary. <br \>
*Instrument (Repeating)*: drop-select of repeating insrument that is the source of the table.
*Columns*: multiple switch-select of columns that should be included into the table rendering. If none is selected, all columns will be rendered. Maximum columns per table is 10!

![image](https://user-images.githubusercontent.com/75415872/143249917-014e0e2c-77e4-4bc2-b634-0bd064d8a013.png)


#### Reordering
Any dashboard structure can be reorderd by using drag & drop.<br>
Rows can be moved by dragging from the row header and dropping before or after another row. The drop zone is on the dashboard canvas area. <br>
Columns can be moved by dragging from the column header and dropping left or right to another column, inside the same row or another row. The drop zone is inside the row area.<br>
Elements can be moved by dragging from the element header and dropping before or after another element inside the same or another column. It is also possible to drop an element into an empty column. The drop zone is insde the column area.

If drag is possible the cursor will be transformed to a move-cross. After drop the new order will be saved automatically.


### Manage Dashboard
The dashboard can be managed through the upper right menu "Manage". The menu consists of different options:

- Import: Import a dashboard through a .json file. The selected file will be roguhly checked for validity. Please be sure that you upload the correct file because after proceeding all current data will be overwritten.
- Export: Export current dashboard as .json file. This can be used to backup the dashboard or reuse in other projects.
- Reset: Erases all dashboard data. This cannot be undone.

![image](https://user-images.githubusercontent.com/75415872/143251518-419e86d3-b61c-47a0-92ae-741f37ba0b3c.png)


### Additional Configuration

#### Display Options
The Editor view is accessible through the External Modules navigation list as "Dashboard Editor". Per default any user assigned to the project can view and edit Dashboard Editor. To disable Editor View for specific users, Display Options can be assigned for different user-roles in the module configuration on project level.

![image](https://user-images.githubusercontent.com/75415872/143218228-10290d8b-c547-4b06-85f7-dbfda77ef688.png)

*Fig. 1: Module configuration on project level for applying Display Options per user-role*

The Render View is visible for any user on the Record Home Page of every Record. Customization of the Record Home Page for specific users can be assigned through Display Options as well.

#### Custom Dashboard title
A custom dashboard title can be set.

